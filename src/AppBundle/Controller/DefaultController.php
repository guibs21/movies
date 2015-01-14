<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    
    /**
     * @Route("/video/{id}", name="videoDetails")
     */
    public function videoDetailsAction($id)
    {
        //récupère le film depuis la bdd, en fonction de son id (présent dans l'URL)
        $videoRepository = $this->getDoctrine()->getRepository("AppBundle:Movie");
        
        // recupere le films par rapport à l'id
        $movie = $videoRepository->find($id);
        
        $params = array(
            "movie" => $movie
        );
        // envoie la vue, en lui passant les variables
        return $this->render('default/videoDetails.html.twig', $params);
        
    }
    
    /**
     * @Route("/{page}", name="homepage", requirements={"page"="\d+"}, defaults={"page" = "1"})
     */
    public function showAllMoviesAction(Request $request, $page)
    {
        
        $numPerPage = 50; // nombre de films à afficher par page
        $offset = ($page - 1) * $numPerPage; // nombre de films à sauter lors de l'affichage
        
        // pour faire un select, on utilise le repository récupère les films depuis la bdd
        $movieRepository = $this->getDoctrine()->getRepository("AppBundle:Movie");
        
        // recup dans la bdd les années min et max
        $moviesMinNumber = $movieRepository->minYear();
        $moviesMaxNumber = $movieRepository->maxYear();
        
        $moviesNumber = $movieRepository->countAll();
        
        $maxPages = ceil($moviesNumber/$numPerPage);
        
        // renvoie les valeurs min et max du slider filtre année
        $minYear = $request->query->get('min') ? $request->query->get('min') : 1900;
        $maxYear = $request->query->get('max') ? $request->query->get('max') : date('Y');
        
        $movies = $movieRepository->filtre($minYear, $maxYear, $offset);
        
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
//        $movies = $movieRepository->findBy(array(), array(
//                            "year" => "DESC",
//                            "title" => "ASC"
//            ), $numPerPage , $offset);
        
        // prépare l'envoi à la vue
        $params = array(
            "movies" => $movies,
            "moviesNumber" => $moviesNumber,
            "moviesMinNumber" => $moviesMinNumber,
            "moviesMaxNumber" => $moviesMaxNumber,
            "maxPages" => $maxPages,
            "numPerPage" => $numPerPage,
            "minYear" => $minYear,
            "maxYear" => $maxYear,
            "currentPage" => $page
        );
        // envoie la vue, en lui passant les variables
        return $this->render('default/index.html.twig', $params);
    }
    
}