<?php
namespace pistol88\order\interfaces;

interface Cart
{
    function getElements();
    function truncate();
}
