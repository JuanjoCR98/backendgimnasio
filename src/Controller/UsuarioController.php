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
           $facebook =$data['facebook'];
           $twitter = $data['instagram'];
           $instagram = $data['twitter'];
        }
        
        $existe_usuario = $this->usuarioRepository->findOneBy(["email" => $email]);
        
        if(empty($email)||empty($password)||empty($nombre)||empty($apellidos)||empty($fecha_nacimiento)){
                return new JsonResponse(['error' => 'Campos obligatorios vacios'], Response::HTTP_NOT_FOUND);
        }
        else if($existe_usuario != null)
        {
            return new JsonResponse(['error' => 'Ya existe un usuario con ese email'], Response::HTTP_NOT_FOUND);
        }
        else if(!filter_var($email,FILTER_VALIDATE_EMAIL))
        {
            return new JsonResponse(['error' => 'Formato de email no v??lido.'], Response::HTTP_NOT_FOUND);
        }
        else if(strlen($password) < 4)
        {
            return new JsonResponse(['error' => 'La contrase??a debe de tener al menos 4 caracteres'], Response::HTTP_NOT_FOUND);
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
                $usuario->setFoto("");
                
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
            return new JsonResponse(['error' => 'Todos los campos son obligatorios. Introduzca todos los campos'], Response::HTTP_NOT_FOUND);
        }
        else if($usuario == null)
        {
            return new JsonResponse(['error' => 'Usuario no v??lido'], Response::HTTP_NOT_FOUND);
        }
        else if(!password_verify($password, $usuario->getPassword()))
        {
           return new JsonResponse(['error' => 'Usuario no v??lido'], Response::HTTP_NOT_FOUND); 
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
                    'fecha_nacimiento' => $usuario->getFechaNacimiento()->format('d-m-Y'),
                    'foto' => $usuario->getFoto(),
                    'rol' => $usuario->getRol(),
                    'facebook'=>$usuario->getRedSocial()->getFacebook(),
                    'twitter' => $usuario->getRedSocial()->getTwitter(),
                    "instagram" => $usuario->getRedSocial()->getInstagram()
                ]; 
            }
            else {
                $estadisticas = [];
                foreach ($usuario->getEstadisticas() as $estadistica)
                {
                    $estadisticas[] = [
                        "id" => $estadistica->getId(),
                        "peso" => $estadistica->getPeso(),
                        "altura" => $estadistica->getAltura(),
                        "imc" => round($estadistica->getImc(),2)
                    ];
                }
                $data = [
                    "id" => $usuario->getId(),
                    "email" => $usuario->getEmail(),
                    "nombre" => $usuario->getNombre(),
                    "apellidos" => $usuario->getApellidos(),
                    "fecha_nacimiento" => $usuario->getFechaNacimiento()->format('d-m-Y'),
                    "rol" => $usuario->getRol(),
                    "estadisticas" => $estadisticas
                ];
            }

            return new JsonResponse($data, Response::HTTP_OK);
        }
        return new JsonResponse(['error' => 'Este usuario no est?? logueado'], Response::HTTP_UNAUTHORIZED);
    }
    
       /**
     * @Route("/usuario/socios", name="get_socios", methods={"GET"})
     */
    public function get_socios() 
    { 
        $socios = $this->usuarioRepository->findBy(["rol" => "socio"]);
        $data=[];
        
        foreach ($socios as $socio) {
            $data[] = [
                "id" => $socio->getId(),
                "email" => $socio->getEmail(),
                "nombre" => $socio->getNombre(),
                "apellidos" => $socio->getApellidos(),
                "fecha_nacimiento" =>  $socio->getFechaNacimiento()->format('Y-m-d')
             ];
        }
        
        if(sizeof($data) == 0)
        {
            return new JsonResponse(["status"=>"No hay ning??n socio"], Response::HTTP_PARTIAL_CONTENT);
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
    
    /**
     * @Route("/usuario/empleados", name="get_empleados", methods={"GET"})
    */
    public function get_empleados() 
    { 
        $empleados = $this->usuarioRepository->findBy(["rol" => "empleado"]);
        
        foreach ($empleados as $empleado) {
            $data[] = [
                'id' => $empleado->getId(),
                'email' => $empleado->getEmail(),
                'nombre' => $empleado->getNombre(),
                'apellidos' => $empleado->getApellidos(),
                'fecha_nacimiento' => $empleado->getFechaNacimiento()->format('Y-m-d'),
                'foto' => $empleado->getFoto(),
                'rol' => $empleado->getRol(),
                'facebook' => $empleado->getRedSocial()->getFacebook(),
                'twitter' => $empleado->getRedSocial()->getTwitter(),
                "instagram" => $empleado->getRedSocial()->getInstagram()
            ];
        }
        
        if(sizeof($data) == 0)
        {
            return new JsonResponse(["status"=>"No hay ning??n empleado"], Response::HTTP_PARTIAL_CONTENT);
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
    
    /**
     * @Route("usuario/socio/{id}" , name="update_socio" , methods={"PUT"})
     */
    public function modificarSocio(int $id,Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);
        $usuario = $this->usuarioRepository->findOneBy(["id" => $id]);
       
        $existe_usuario = $this->usuarioRepository->findOneBy(array("email" => $data["email"]));

        $fecha_nacimiento = $data['fecha_nacimiento'];
        $fecha = new DateTime($fecha_nacimiento);
        if ($usuario != null) 
        {
            if (($usuario->getEmail() == $data["email"]) || $existe_usuario == null) {
                
                if (!empty($data["email"]) && !filter_var($data["email"], FILTER_VALIDATE_EMAIL)) 
                {
                    return new JsonResponse(['error' => 'Introduzca un email v??lido'], Response::HTTP_NOT_FOUND);
                } 
                else if (!empty($data["password"]) && strlen($data["password"]) < 4) 
                {
                    return new JsonResponse(['error' => 'La contrase??a debe de tener al menos 4 caracteres'], Response::HTTP_NOT_FOUND);
                } 
                else 
                {
                    empty($data["nombre"]) ? true : $usuario->setNombre($data["nombre"]);
                    empty($data["apellidos"]) ? true : $usuario->setApellidos($data["apellidos"]);
                    empty($data["fecha_nacimiento"]) ? true : $usuario->setFechaNacimiento($fecha);
                    empty($data["email"]) ? true : $usuario->setEmail($data["email"]);
                    empty($data["password"]) ? true : $usuario->setPassword(password_hash($data["password"], PASSWORD_BCRYPT));
      
                    $this->usuarioRepository->updateUsuario($usuario);

                    return new JsonResponse(['status' => 'Se ha actualizado correctamente'], Response::HTTP_OK);
                }
            } 
            else 
            {
                return new JsonResponse(['error' => 'Ya existe un usuario con ese email'], Response::HTTP_NOT_FOUND);
            }
        }
        else
        {
            return new JsonResponse(["error" => "No hay ning??n socio con ese id"], Response::HTTP_NOT_FOUND);
        }
    }
    
        /**
     * @Route("usuario/empleado/{id}" , name="update_empleado" , methods={"PUT"})
     */
    public function modificarEmpleado(int $id, Request $request): JsonResponse 
    {
        $empleado = $this->usuarioRepository->findOneBy(["id" => $id]);

        $data = json_decode($request->getContent(), true);

        $fecha_nacimiento = $data['fecha_nacimiento'];
        $fecha = new DateTime($fecha_nacimiento);
       
        $existe_email = $this->usuarioRepository->findOneBy(array("email" => $data["email"]));
 
        if ($empleado != null) 
        {
            if (($empleado->getEmail() == $data["email"]) || $existe_email == null) 
            {
                if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) 
                {
                    return new JsonResponse(['error' => 'Formato de email no v??lido.'], Response::HTTP_NOT_FOUND);
                } 
                else if (!empty($data["password"]) && strlen($data["password"]) < 4) 
                {
                    return new JsonResponse(['error' => 'La contrase??a debe de tener al menos 4 caracteres'], Response::HTTP_NOT_FOUND);
                } 
                else {
                    empty($data["email"]) ? true : $empleado->setEmail($data["email"]);
                    empty($data["nombre"]) ? true : $empleado->setNombre($data["nombre"]);
                    empty($data["apellidos"]) ? true : $empleado->setApellidos($data["apellidos"]);
                    empty($data["password"]) ? true : $empleado->setPassword(password_hash($data["password"], PASSWORD_BCRYPT));
                   empty($data["fecha_nacimiento"]) ? true : $empleado->setFechaNacimiento($fecha);
                    empty($data["rol"]) ? true : $empleado->setRol($data["rol"]);
                    empty($data["facebook"]) ? true : $empleado->getRedSocial()->setFacebook($data["facebook"]);
                    empty($data["twitter"]) ? true : $empleado->getRedSocial()->setTwitter($data["twitter"]);
                    empty($data["instagram"]) ? true : $empleado->getRedSocial()->setInstagram($data["instagram"]);
                    
                    $this->usuarioRepository->updateUsuario($empleado);
                }
                return new JsonResponse(['status' => 'Se ha actualizado correctamente'], Response::HTTP_OK);
            } 
            else {
                return new JsonResponse(['error' => 'Ya existe un usuario con ese email'], Response::HTTP_NOT_FOUND);
            }
        } 
        else {
            return new JsonResponse(["error" => "No hay ning??n empleado con ese id"], Response::HTTP_NOT_FOUND);
        }
    }
    
           /**
     * @Route("usuario/image/{id}" , name="subir_imagen" , methods={"POST"})
     */
    public function subirImagen(int $id, Request $request): JsonResponse 
    {
        $empleado = $this->usuarioRepository->findOneBy(["id" => $id]);
        $root = "C:/xampp/htdocs/ProyectoFinalDaw/backendgimnasio/";
        if (isset($_FILES['foto']))
        {
            $foto_empleado = $_FILES['foto'];
            $mime = $foto_empleado['type'];
            $size = $foto_empleado['size'];
            $rutaTemporal = $foto_empleado['tmp_name'];
            
            $extension = strpos($mime, "jpeg") ? ".jpg": ".png";
            $nameFoto = "empleado".$id.$extension;
            $ruta = $root . "public/imagenes/".$nameFoto;
            
            $existeImagen = $root . "public/imagenes/empleado".$id."*";
            $imagen = glob($existeImagen);
            
            foreach ($imagen as $img)
            {
                unlink($img);
            }
            
         if (move_uploaded_file($rutaTemporal, $ruta)) {
             $fotofinal = "http://localhost/ProyectoFinalDaw/backendgimnasio/public/imagenes/".$nameFoto;
             $empleado->setFoto($fotofinal);
             $this->usuarioRepository->updateUsuario($empleado);
             return new JsonResponse(['status' => 'Se ha subido correctamente'], Response::HTTP_OK);
        } else {
            return new JsonResponse(["error" => "??Posible ataque de subida de ficheros!"], Response::HTTP_NOT_FOUND);
        }
       }
       return new JsonResponse(["error" => "No has seleccionado ninguna imagen"], Response::HTTP_PARTIAL_CONTENT);
    }
     /**
     * @Route("usuario/user/{id}", name="delete_user", methods={"DELETE"})
     */
    public function deleteUser(int $id): JsonResponse
    {
        $usuario = $this->usuarioRepository->findOneBy(['id' => $id]);
        
        $this->usuarioRepository->removeUsuario($usuario);
        
        return new JsonResponse(['status' => 'Se ha borrado correctamente'], Response::HTTP_OK);
    }
}
