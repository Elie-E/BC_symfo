/**
 * Side navigation
 */
let btn = document.querySelector("#btn");
let sidebar = document.querySelector(".sidebar");
let searchBtn = document.querySelector(".bx-search");

btn.onclick = function(){
    sidebar.classList.toggle("active");
}
searchBtn.onclick = function(){
    sidebar.classList.toggle("active");
}

/**
 * Set content in the middle if the pointer is passed on the navigation icons
 */
const contentToFill = document.querySelector('#admin-page_content_container');

let buttons = document.querySelectorAll('#admin_button');
buttons.forEach(button => {
    button.addEventListener('mouseover', function(e) {

        contentToFill.classList.add('with-shadow');
        
        switch(this.getAttribute('href')) {
            case '/client/':
                console.log(this.getAttribute('href'));
                contentToFill.innerHTML = "Voir la liste des clients";
                break;
            case '/product/':
                console.log(this.getAttribute('href'));
                contentToFill.innerHTML = "Voir la liste des produits";
                break;
            case '/order/':
                console.log(this.getAttribute('href'));
                contentToFill.innerHTML = "Voir la liste des commandes";
                break;
            case '/category/':
                console.log(this.getAttribute('href'));
                contentToFill.innerHTML = "Voir la liste des catégories";
                break;
            case '/brand/':
                console.log(this.getAttribute('href'));
                contentToFill.innerHTML = "Voir la liste des marques";
                break;
            case '/':
                console.log(this.getAttribute('href'));
                contentToFill.innerHTML = 'Revenir à la boutique';
                break;
            default :
                console.log('zzzz');
        }
    })
});