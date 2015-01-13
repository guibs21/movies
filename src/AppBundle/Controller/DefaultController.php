<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    /**
     * @Route("/video/{id}", name="videoDetails")
     */
    public function videoDetailsAction($id)
    {
        // pour faire un select, on utilise le repository
        $videoRepository = $this->getDoctrine()->getRepository("AppBundle:Movie");
        
        // recupere le films par rapport à l'id
        $movie = $videoRepository->find($id);
        
        $params = array(
            "movie" => $movie
        );
        
        return $this->render('default/videoDetails.html.twig', $params);
        
    }
    
    /**
     * @Route("/{page}", name="homepage", requirements={"page"="\d+"}, defaults={"page" = "1"})
     */
    public function showAllMoviesAction($page)
    {
        // pour faire un select, on utilise le repository
        $movieRepository = $this->getDoctrine()->getRepository("AppBundle:Movie");
        
        $numPerPage = 50; // nombre de films à afficher par page
        $offset = ($page - 1) * $numPerPage; // nombre de films à sauter lors de l'affichage
        
        
        
        // recup dans la bdd les années min et max
        $moviesMinNumber = $movieRepository->minYear();
        $moviesMaxNumber = $movieRepository->maxYear();
        
        
        $moviesNumber = $movieRepository->countAll();
        
        $maxPages = ceil($moviesNumber/$numPerPage);
        
        // si l'uilisateur a deconner avec l'url ..
        // on redirige vers la derniere page
        if ($page > $maxPages) {
            return $this->redirect(
                    $this->generateUrl("homepage", array  ("page" => $maxPages))
                    );
        }
        // à l'inverse, page trop petite
        // si sur la page "0" par exemple
        elseif($page < 1){
            return $this->redirect(
                    $this->generateUrl("homepage", array  ("page" => 1))
                    );
        }
        
        // recupere nos films depuis la bdd
        // premier argument du findBy => classes Where, pas utilisé ici 
        // deuxieme argument => classe ORDER BY
        // troisieme =>parametre LIMIT (50 filmd par page max)
        // quatrieme => offset, 
        $movies = $movieRepository->findBy(array(), array(
                            "year" => "DESC",
                            "title" => "ASC"
            ), $numPerPage , $offset);
        
        
        // creer un tableau associatif des valeurs à passer à Twig
        // les clefs seront les noms des variables Twig
        // les valeurs seront les valeurs des variables Twig
        $params = array(
            "movies" => $movies,
            "moviesNumber" => $moviesNumber,
            "moviesMinNumber" => $moviesMinNumber,
            "moviesMaxNumber" => $moviesMaxNumber,
            "maxPages" => $maxPages,
            "numPerPage" => $numPerPage,
            "currentPage" => $page
        );
        
        return $this->render('default/index.html.twig', $params);
    }
    
    
    
}