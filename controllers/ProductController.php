<?php
require_once ROOTPATH . '/models/ProductModel.php';

class ProductController {
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    public function catalogAction() {
        // Fetch all products from the database
        $products = $this->productModel->getAllProducts();
        
        // Render the catalog view, passing the data
        $pageTitle = "Cozyhomes Catalog";
        
        include ROOTPATH . '/views/layout/header.php';
        // The $products variable will be available in the included view
        include ROOTPATH . '/views/product/catalog.php'; 
        include ROOTPATH . '/views/layout/footer.php';
    }
}