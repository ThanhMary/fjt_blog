<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class ArticleSearch {

   /**
    * @var string
    */
    public $q ='';

    /**
    * @var Category[]
    */

    public $categories = [];

    public $author;
    
    public $creationDate;

}

