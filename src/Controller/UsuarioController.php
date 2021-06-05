<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UsuarioRepository;
use Firebase\JWT\JWT;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Security\JwtAuthenticator;
use App\Entity\Usuario;
use App\Entity\RedSocial;
use DateTime;

class UsuarioController extends AbstractController
{
    private $usuarioRepository;
    
    function __construct(UsuarioRepository $usuarioRepository) {
        $this->usuarioRepository = $usuarioRepository;
    }

     /**
     * @Route("/usuario/registrar", name="registrar", methods={"POST"})
     */
    public function registrar(Request $request) {
        $data = json_decode($request->getContent(),true);
        
        $rol = $data['rol'];
         
        $email = $data['email'];
        $password = $data['password'];
        $nombre = $data['nombre'];
        $apellidos = $data['apellidos'];
        $fecha_nacimiento = $data['fecha_nacimiento'];
        
        if($rol == "empleado")
        {
           $foto= $data['foto'];
           $facebook =$data['facebook'];
           $twitter = $data['instagram'];
           $instagram = $data['twitter'];
        }
        
        $existe_usuario = $this->usuarioRepository->findOneBy(["email" => $email]);
        
        if(empty($email)||empty($password)||empty($nombre)||empty($apellidos)||empty($fecha_nacimiento)){
                return new JsonResponse(['error' => 'Campos obligatorios vacios'], Response::HTTP_PARTIAL_CONTENT);
        }
        else if($existe_usuario != null)
        {
            return new JsonResponse(['error' => 'Ya existe un usuario con ese email'], Response::HTTP_PARTIAL_CONTENT);
        }
        else if(!filter_var($email,FILTER_VALIDATE_EMAIL))
        {
            return new JsonResponse(['error' => 'Formato de email no válido.'], Response::HTTP_PARTIAL_CONTENT);
        }
        else if(strlen($password) < 4)
        {
            return new JsonResponse(['error' => 'La contraseña debe de tener al menos 4 caracteres'], Response::HTTP_PARTIAL_CONTENT);
        }
        else
        {
            $usuario = new Usuario();
            $usuario->setEmail($email);
            $usuario->setPassword(password_hash($password, PASSWORD_BCRYPT));
            $usuario->setNombre($nombre);
            $usuario->setApellidos($apellidos);
            $usuario->setFechaNacimiento(new DateTime($fecha_nacimiento));
            $usuario->setRol($rol);
            if($rol == "empleado")
            {
                $usuario->setFoto($foto);
                
                $redSocial = new RedSocial();
                $redSocial->setFacebook($facebook);
                $redSocial->setInstagram($instagram);
                $redSocial->setTwitter($twitter);
                
                $usuario->setRedSocial($redSocial);
            }
            $this->usuarioRepository->saveUsuario($usuario);
            
            return new JsonResponse(['status'=>"Usuario creado"],Response::HTTP_OK);
        }     
    }
    /**
     * @Route("/usuario/login", name="login", methods={"POST"})
    */
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $email = $data['email'];
        $password = $data['password'];
        
        $usuario = $this->usuarioRepository->findOneBy(['email' => $email]);

        if(empty($email) || empty($password))
        {
            return new JsonResponse(['error' => 'Todos los campos son obligatorios. Introduzca todos los campos'], Response::HTTP_PARTIAL_CONTENT);
        }
        else if($usuario == null)
        {
            return new JsonResponse(['error' => 'Usuario no válido'], Response::HTTP_NOT_FOUND);
        }
        else if(!password_verify($password, $usuario->getPassword()))
        {
           return new JsonResponse(['error' => 'Usuario no válido'], Response::HTTP_NOT_FOUND); 
        }
        else{
           $payload = [
                "user" => $usuario->getEmail(),
                "exp" => (new \DateTime())->modify("+5 day")->getTimestamp(),
            ];

            $jwt = JWT::encode($payload, $this->getParameter('jwt_secret'), 'HS256');

            return new JsonResponse([
                'respuesta' => 'Usuario logueado correctamente',
                'token' => $jwt,
                    ], Response::HTTP_OK);
        } 
    }
    
    /**
     * @Route("/usuario", name="get_user", methods={"GET"})
     */
    public function get_user(Request $request, ParameterBagInterface $params, UserProviderInterface $userProvider) {
        $em = $this->getDoctrine()->getManager();
        $jwtauth = new JwtAuthenticator($em, $params);

        $credentials = $jwtauth->getCredentials($request);

        $usuario = $jwtauth->getUser($credentials, $userProvider);
       
        if ($usuario) {
            
            if($usuario->getRol() == "empleado")
            {
               $data = [
                    'id' => $usuario->getId(),
                    'email' => $usuario->getEmail(),
                    'nombre' => $usuario->getNombre(),
                    'apellidos' => $usuario->getApellidos(),
                    'fecha_nac' => $usuario->getFechaNacimiento(),
                    'foto' => $usuario->getFoto(),
                    'rol' => $usuario->getRol(),
                    'facebook'=>$usuario->getRedSocial()->getFacebook(),
                    'twitter' => $usuario->getRedSocial()->getTwitter(),
                    "instagram" => $usuario->getRedSocial()->getInstagram()
                ]; 
            }
            else {
                $data = [
                    "id" => $usuario->getId(),
                    "email" => $usuario->getEmail(),
                    "nombre" => $usuario->getNombre(),
                    "apellidos" => $usuario->getApellidos(),
                    "fecha_nac" => $usuario->getFechaNacimiento(),
                    "rol" => $usuario->getRol()
                ];
            }

            return new JsonResponse($data, Response::HTTP_OK);
        }
        return new JsonResponse(['error' => 'Este usuario no está logueado'], Response::HTTP_UNAUTHORIZED);
    }
    
       /**
     * @Route("/usuario/socios", name="get_socios", methods={"GET"})
     */
    public function get_socios() 
    { 
        $socios = $this->usuarioRepository->findBy(["rol" => "socio"]);
        
        foreach ($socios as $socio) {
            $data[] = [
                "id" => $socio->getId(),
                "email" => $socio->getEmail(),
                "nombre" => $socio->getNombre(),
                "apellidos" => $socio->getApellidos(),
                "fecha_nac" => $socio->getFechaNacimiento()
             ];
        }
        
        if(sizeof($data) == 0)
        {
            return new JsonResponse(["status"=>"No hay ningún socio"], Response::HTTP_PARTIAL_CONTENT);
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
    
    /**
     * @Route("/usuario/empleados", name="get_empleados", methods={"GET"})
    */
    public function get_empleados() 
    { 
        $empleados = $this->usuarioRepository->findBy(["rol" => "empleado"]);
        
        foreach ($empleados as $empleados) {
            $data = [
                'id' => $empleados->getId(),
                'email' => $empleados->getEmail(),
                'nombre' => $empleados->getNombre(),
                'apellidos' => $empleados->getApellidos(),
                'fecha_nac' => $empleados->getFechaNacimiento(),
                'foto' => $empleados->getFoto(),
                'rol' => $empleados->getRol(),
                'facebook' => $empleados->getRedSocial()->getFacebook(),
                'twitter' => $empleados->getRedSocial()->getTwitter(),
                "instagram" => $empleados->getRedSocial()->getInstagram()
            ];
        }
        
        if(sizeof($data) == 0)
        {
            return new JsonResponse(["status"=>"No hay ningún empleado"], Response::HTTP_PARTIAL_CONTENT);
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
