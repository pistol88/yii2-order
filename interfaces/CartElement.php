<?php
namespace pistol88\order\interfaces;

interface CartElement
{
    function getId();
    function getName();
    function getPrice($withTriggers = true);
    function getCount();
    function getModelName();
    function getItemId();
    function getOptions();
}
