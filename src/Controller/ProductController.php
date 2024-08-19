<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductFilterType;
use App\Form\ProductImportType;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository, Request $request, PaginatorInterface $paginator): Response
    {
        // filtering
        $queryBuilder = $productRepository->createQueryBuilder('p');
        $form = $this->createForm(ProductFilterType::class, null, [
            'method' => 'GET',
            'data' => $request->query->all(),
            'attr' => ['class' => 'row g-3 m-t-10'],
            
        ]);                  
        $form->handleRequest($request);
        // dd($request);
        // dd($form->getData());
        
        if ($request->query->has('product_filter')) {
            $fdata = $form->getData();      

            if (!is_null($fdata['priceMin'])) {
                $queryBuilder->andWhere('p.price >= :priceMin')->setParameter('priceMin', $fdata['priceMin']);
            }
            if (!is_null($fdata['priceMax'])) {
                $queryBuilder->andWhere('p.price <= :priceMax')->setParameter('priceMax', $fdata['priceMax']);
            }
            if (!is_null($fdata['stockQuantity'])) {
                $queryBuilder->andWhere('p.stockQuantity = :stockQuantity')->setParameter('stockQuantity', $fdata['stockQuantity']);
            }
            if (!is_null($fdata['createdDate'])) {
                $inputDate = clone $fdata['createdDate'];
                $startOfDay  = clone $inputDate->setTime(0, 0, 0);
                $endOfDay  = clone $inputDate->setTime(23, 59, 59);
                $queryBuilder->andWhere('p.createdDatetime BETWEEN :start AND :end')
                            ->setParameter('start', $startOfDay)
                            ->setParameter('end', $endOfDay);
            }
        }        
        
        // sorting
        $sort = $request->query->get('sort', 'p.createdDatetime');
        $direction = $request->query->get('direction', 'desc');
        $queryBuilder->orderBy($sort, $direction);        

        $query = $queryBuilder->getQuery();
        $products = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
        );        

        return $this->render('product/index.html.twig', [
            'pagination' => $products,
            'form' => $form,
            'sort' => $sort,
            'direction' => $direction,
            'button_label' => 'Search',
            'showResetButton' => true,
        ]);
    }

    #[Route('/import', name: 'product_import', methods: ['GET', 'POST'])]
    public function import(Request $request, EntityManagerInterface $entityManager): Response
    {
        // a form to handle CSV file upload
        $form = $this->createForm(ProductImportType::class);
        $form->handleRequest($request);

        if ($request->isMethod('POST') && $form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('csv_file')->getData();

            if ($file) {
                // Get the file path in the temporary directory
                $filePath = $file->getRealPath();
                
                try {
                    // Open the file and read its contents
                    $this->processCsvFile($filePath, $entityManager);                                       
                } catch (FileException $e) {
                    echo $e->getMessage() . "\n";
                }
            }
            
            // flash message
            $this->addFlash('success', [
                'message' => 'Products imported successfully!',
                'dismissible' => true,
                'showButtonClose' => true, // Set this to false if you don't want the close button
            ]);

            // Redirect or return a response after processing
            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/import.html.twig', [
            'form' => $form,
            'button_label' => 'Import',
            'button_icon' => 'ti-import'
        ]);
    }    

    #[Route('/export', name: 'product_export', methods: ['GET'])]    
    public function export(ProductRepository $productRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $queryBuilder = $productRepository->createQueryBuilder('p');
        $form = $this->createForm(ProductFilterType::class, null, [
            'method' => 'GET',
            'data' => $request->query->all(),
        ]);                  
        $form->handleRequest($request);

        // filtering
        if ($request->query->has('product_filter')) {
            $fdata = $form->getData();      

            if (!is_null($fdata['priceMin'])) {
                $queryBuilder->andWhere('p.price >= :priceMin')->setParameter('priceMin', $fdata['priceMin']);
            }
            if (!is_null($fdata['priceMax'])) {
                $queryBuilder->andWhere('p.price <= :priceMax')->setParameter('priceMax', $fdata['priceMax']);
            }
            if (!is_null($fdata['stockQuantity'])) {
                $queryBuilder->andWhere('p.stockQuantity = :stockQuantity')->setParameter('stockQuantity', $fdata['stockQuantity']);
            }
            if (!is_null($fdata['createdDate'])) {
                $inputDate = clone $fdata['createdDate'];
                $startOfDay  = clone $inputDate->setTime(0, 0, 0);
                $endOfDay  = clone $inputDate->setTime(23, 59, 59);
                $queryBuilder->andWhere('p.createdDatetime BETWEEN :start AND :end')
                            ->setParameter('start', $startOfDay)
                            ->setParameter('end', $endOfDay);
            }
        }        
        
        // sorting
        $sort = $request->query->get('sort', 'p.createdDatetime');
        $direction = $request->query->get('direction', 'desc');
        $queryBuilder->orderBy($sort, $direction);        
        
        // Create CSV content in chunks    
        $chunkSize = 100;
        $totalProducts = (clone $queryBuilder)->select('count(p.id)')->getQuery()->getSingleScalarResult();
        $output = fopen('php://memory', 'r+');
        fputcsv($output, ['Name', 'Description', 'Price', 'Stock Quantity', 'Created Datetime']);
        for ($i = 0; $i < $totalProducts; $i += $chunkSize) {
            $queryBuilder->setFirstResult($i)->setMaxResults($chunkSize);
            $products = $queryBuilder->getQuery()->getResult();

            foreach ($products as $product) {
                fputcsv($output, [
                    $product->getName(),
                    $product->getDescription(),
                    $product->getPrice(),
                    $product->getStockQuantity(),
                    $product->getCreatedDatetime()->format('Y-m-d H:i:s'),
                ]);
            }
            rewind($output);

            // Clear the entity manager to free up memory
            $entityManager->clear();
        }

        // Create a response with CSV content
        $response = new Response(stream_get_contents($output));
        fclose($output);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment;filename="products.csv"');        

        return $response;
    }

    #[Route('/new', name: 'product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            // flash message
            $this->addFlash('success', [
                'message' => 'Product created successfully!',
                'dismissible' => true,
                'showButtonClose' => true, // Set this to false if you don't want the close button
            ]);

            return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'product_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'product_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // flash message
            $this->addFlash('success', [
                'message' => 'Product updated successfully!',
                'dismissible' => true,
                'showButtonClose' => true, // Set this to false if you don't want the close button
            ]);

            return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'product_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();

            // flash message
            $this->addFlash('success', [
                'message' => 'Product deleted successfully!',
                'dismissible' => true,
                'showButtonClose' => true, // Set this to false if you don't want the close button
            ]);

            
            return new JsonResponse([
                'status' => 'success',
                'redirectUrl' => $this->generateUrl('product_index')
            ], Response::HTTP_OK);            
        }        

        return new JsonResponse([
            'message' => 'Error occurred while deleting the product!'
        ], Response::HTTP_FORBIDDEN);
    }

    private function processCsvFile($filePath, EntityManagerInterface $entityManager)
    {
        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                // Create a new Product entity and set its properties
                $product = new Product();
                $product->setName($data[0]);
                $product->setDescription($data[1]);
                $product->setPrice($data[2]);
                $product->setStockQuantity($data[3]);

                // Save the product to the database                
                $entityManager->persist($product);
            }
            fclose($handle);

            // Flush the entity manager to save all products
            $entityManager->flush();
        }
    }
}
