<?php
namespace pistol88\order\interfaces;

interface User
{
    function getUserProfile();
    function getEmail();
    function getPhone();
    function getName();
    function getFullName();
}
