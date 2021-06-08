<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UsuarioRepository;
use App\Repository\EstadisticaRepository;
use App\Entity\Estadistica;

class EstadisticaController extends AbstractController
{
    
    private $usuarioRepository;
    private $estadisticaRepository;
    
    function __construct(UsuarioRepository $usuarioRepository,EstadisticaRepository $estadisticaRepository) {
        $this->usuarioRepository = $usuarioRepository;
        $this->estadisticaRepository = $estadisticaRepository;  
    }

    /**
     * @Route("/estadistica/socio" , name="anadir_estadistica" , methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $peso = (float) $data["peso"];
        $altura = (float) $data["altura"];
        $id = $data["usuario"];

	$usuario = $this->usuarioRepository->findOneBy(array("id" => $id));

        if ($usuario != null) 
        {
            if (empty($peso) || empty($altura)) 
            {
                return new JsonResponse(['error' => 'Todos los campos son obligatorios. Introduzca todos los campos'], Response::HTTP_PARTIAL_CONTENT);
            } 
            else 
            {
                $estadistica = new Estadistica();

                $estadistica->setPeso($peso);
                $estadistica->setAltura($altura);
                $estadistica->setImc($peso/($altura*$altura));
                $estadistica->setUsuario($usuario);

                $this->estadisticaRepository->saveEstadistica($estadistica);

                return new JsonResponse(['status' => 'Se ha registrado correctamente'], Response::HTTP_OK);
            }
        } 
        else {
            return new JsonResponse(['error' => 'No existe ningún socio con ese id'], Response::HTTP_PARTIAL_CONTENT);
        }
    }
}
