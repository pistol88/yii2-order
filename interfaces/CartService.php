<?php
namespace pistol88\cart\interfaces;

interface CartService
{
    public function my();
    
    public function put(ElementService $model);
    
    public function getElements();
    
    public function getElement(CartElement $model, $options);
    
    public function getCost();
    
    public function getCount();
    
    public function getElementById($id);
    
    public function getElementsByModel(CartElement $model);
    
    public function truncate();
}
