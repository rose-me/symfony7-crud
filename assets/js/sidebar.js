// Trigger the dropdown toggler
function toggleDropdownMenu(event) {
    const parentElement = event.currentTarget.parentElement;
    const dropdownMenu = event.currentTarget.nextElementSibling;
    parentElement.classList.toggle('show');
    dropdownMenu.classList.toggle('show');
    
    // Close other dropdown menu
    document.querySelectorAll('.mypj-submenu').forEach(subMenu => {
        if (subMenu !== dropdownMenu) {
            subMenu.classList.remove('show');
            event.classList.remove('show');
        }
    });
}
// Add event listener to all dropdown toggles
document.querySelectorAll('.mypj-navbar .mypj-hasmenu .dropdown-toggle').forEach(link => {
    link.addEventListener('click', toggleDropdownMenu);
});

// Function to handle click events for sidebar menu item elements without submenu
function handleNavItemClick(event) {
    const navItem = event.currentTarget;

    // Remove 'active' class from any currently active nav-item
    const currentlyActiveNavItem = document.querySelector('.nav-item.active');
    if (currentlyActiveNavItem && currentlyActiveNavItem !== navItem) {
        currentlyActiveNavItem.classList.remove('active');

        // Also remove 'active' from submenu items of the previously active nav-item
        const activeSubmenuItem = currentlyActiveNavItem.querySelector('.mypj-submenu li.active');
        activeSubmenuItem.classList.remove('active');
        // activeSubmenuItems.forEach(item => item.classList.remove('active'));

        // Also remove 'show' from submenu
        const activeSubmenu = currentlyActiveNavItem.querySelector('.mypj-submenu');
        activeSubmenu.classList.remove('show');

        // Also remove 'show' from dropdown toggler
        const activeDropdownToggler = currentlyActiveNavItem.querySelector('.dropdown-toggle');
        activeDropdownToggler.classList.remove('show');
    }    
}
// Add event listener to all sidebar menu item elements without submenu
document.querySelectorAll('.nav-item:not(.mypj-hasmenu)').forEach(item => {
    item.addEventListener('click', handleNavItemClick);
});

function clickSubMenuItem(event) {
    // event.preventDefault();
    const clickedLink = event.currentTarget;
    const parentElement = event.currentTarget.parentElement;

    // Find the closest parent 'li' with the class 'nav-item'
    const navItem = clickedLink.closest('.nav-item');

    // Remove 'active' class from any currently active nav-item
    const currentlyActiveNavItem = document.querySelector('.nav-item.active');
    if (currentlyActiveNavItem && currentlyActiveNavItem !== navItem) {
        console.log('Remove active.....')
        currentlyActiveNavItem.classList.remove('active');

        // Also remove 'active' from submenu items of the previously active nav-item
        const activeSubmenuItems = currentlyActiveNavItem.querySelectorAll('.mypj-submenu li.active');
        activeSubmenuItems.forEach(item => item.classList.remove('active'));
    }    
}
// Add event listener to all sidebar submenu item
document.querySelectorAll('.mypj-navbar .mypj-submenu .dropdown-item').forEach(link => {
    link.addEventListener('click', clickSubMenuItem);
});
