document.addEventListener("DOMContentLoaded", function(event) {


    const cartButtons = document.querySelectorAll('.ee_product_card-cart-button');
    
    cartButtons.forEach(button => {
    
    button.addEventListener('click',cartClick);
    
    });
    
    function cartClick(){
    let button =this;
    button.classList.add('clicked');
    }
  
});