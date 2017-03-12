<?php
namespace pistol88\order\interfaces;

interface Order
{
    function getId();
    function getElements();
    function getClient();
    function getSeller();
}
