// Function to update the div tag based on screen size
function updateResponsiveAttribute() {
    const div = document.getElementById('mypj');
    // console.log('body....', window.innerWidth)
    
    // To use at sidebar display
    if (window.innerWidth >= 992) { // For larger devices
        div.setAttribute('vertical-nav-type', 'expanded');
        div.setAttribute('vertical-effect', 'shrink');
    } else { // For small devices
        div.setAttribute('vertical-nav-type', 'offcanvas');
        div.setAttribute('vertical-effect', 'overlay');
    }

    if (window.innerWidth >= 992) { // For desktop
        console.log('Applied desktop styles');
        div.setAttribute('mypj-device-type', 'desktop');
    } else if (window.innerWidth >= 768 && window.innerWidth < 992) { // For tablet
        console.log('Applied tablet styles');
        div.setAttribute('mypj-device-type', 'tablet');
    } else { // For mobile
        console.log('Applied mobile styles');
        div.setAttribute('mypj-device-type', 'mobile');
    }
}

// Function to update the div tag when click sidebar toggler
function updateNavTypeAttribute() {
    const div = document.getElementById('mypj');

    // Check the current value of the attribute
    var currentNavType = div.getAttribute('vertical-nav-type');

    // Toggle between 'expanded' and 'offcanvas'    
    if (currentNavType === 'offcanvas') {
        console.log('offcanvas to expanded...');
        div.setAttribute('vertical-nav-type', 'expanded');
    } else {
        console.log('expanded to offcanvas...');
        div.setAttribute('vertical-nav-type', 'offcanvas');
    }

}

// Run the function on page load
window.addEventListener('load', updateResponsiveAttribute);

// Run the function on window resize
window.addEventListener('resize', updateResponsiveAttribute);

// Add event listener to header's sidebar toggler icon (...)
document.getElementById('sidebar-toggler-collapse').addEventListener('click', updateNavTypeAttribute);