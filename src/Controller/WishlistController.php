<?php

namespace App\Controller;

use App\Entity\MenuElement;
use App\Form\MenuElementType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Slugify;
use App\Repository\ArticleRepository;
use Doctrine\Common\Persistence\ObjectManager;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
class WishlistController extends AbstractController
{

}
