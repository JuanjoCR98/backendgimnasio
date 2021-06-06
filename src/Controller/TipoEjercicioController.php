<?php

namespace App\Controller;

use App\Repository\TipoEjercicioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class TipoEjercicioController extends AbstractController
{
    private $tipoEjercicioRepository;

    public function __construct(TipoEjercicioRepository $tipoEjercicioRepository)
    {
        $this->tipoEjercicioRepository = $tipoEjercicioRepository;
    }

     /**
     * @Route("/tipo_ejercicio", name="tipo_ejercicio" , methods={"GET"})
     */
    public function tipo_ejercicios(): JsonResponse {
        $tipo_ejercicios = $this->tipoEjercicioRepository->findAll();
        $data=[];
        
        foreach($tipo_ejercicios as $tipo){
            $data[] = [
                'id' => $tipo->getId(),
                'tipo' => $tipo->getTipo()
            ];
        }
        return new JsonResponse($data,Response::HTTP_OK);
    }
    
    /**
     * @Route("/tipo_ejercicio/ejercicios", name="tipo_ejercicio_ejercicios" , methods={"GET"})
     */
    public function tipo_ejercicios_ejercicios(): JsonResponse {
        $tipo_ejercicios = $this->tipoEjercicioRepository->findAll();
        
        $data=[];
        
        foreach($tipo_ejercicios as $tipo){
            $ejercicios=[];
            
            foreach ($tipo->getEjercicios() as $ejercicio){
               $ejercicios[] = [
                   'id' => $ejercicio->getId(),
                   'nombre'=>$ejercicio->getNombre(),
                   'ejecucion'=>$ejercicio->getEjecucion(),
                   'foto'=>$ejercicio->getFoto(),
                   'tipo'=>$ejercicio->getTipoEjercicio()->getTipo()
               ];
            }
            $data[] = [
                'id' => $tipo->getId(),
                'tipo' => $tipo->getTipo(),
                'ejercicios' => $ejercicios
            ];
        }
        return new JsonResponse($data,Response::HTTP_OK);
    }
}
