php
<?php

namespace App\Controller\Api;

use App\Entity\Toy;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
* @Route("/api/toys")
*/

// описываем контроллер
class ToysCrudController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     */

    // метод GetCollection
    public function getCollection(): JsonResponse
    {
        $toys = $this->getDoctrine()->getRepository(Toy::class)->findAll();

        // Функция array_map возвращает массив, содержащий результаты применения функции обратного действия к соответствующему элементу
        $data = array_map(function (Toy $toy) {
            return [
                'id' => $toy->getId(),
                'name' => $toy->getName(),
                'price' => $toy->getPrice(),
                'description' => $toy->getDescription(),
            ];
        }, $toys);

        return new JsonResponse($data);
    }

    /**
     * @Route("/", methods={"POST"})
     */

    // метод post

    public function post(Request $request): JsonResponse
    {
        // json_decode декодирует строку JSON -  преобразует её в PHP-значение
        $data = json_decode($request->getContent(), true);

        $toy = new Toy();
        $toy->setName($data['name']);
        $toy->setPrice($data['price']);
        $toy->setDescription($data['description']);

        // менеджер сущностей
        $entityManager = $this->getDoctrine()->getManager();
        // сообщает доктрин что я хочу сохранить объект
        $entityManager->persist($toy);
        //выполнение запроса
        $entityManager->flush();

        return new JsonResponse([
            'id' => $toy->getId(),
            'name' => $toy->getName(),
            'price' => $toy->getPrice(),
            'description' => $toy->getDescription(),
        ], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", methods={"GET"})
     */

    // метод GetItem
    public function getItem(Toy $toy): JsonResponse
    {
        return new JsonResponse([
            'id' => $toy->getId(),
            'name' => $toy->getName(),
            'price' => $toy->getPrice(),
            'description' => $toy->getDescription(),
        ]);
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     */

    // метод Put
    public function put(Toy $toy, Request $request): JsonResponse
    {
        // json_decode декодирует строку JSON -  преобразует её в PHP-значение
        $data = json_decode($request->getContent(), true);

        $toy->setName($data['name']);
        $toy->setPrice($data['price']);
        $toy->setDescription($data['description']);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return new JsonResponse([
            'id' => $toy->getId(),
            'name' => $toy->getName(),
            'price' => $toy->getPrice(),
            'description' => $toy->getDescription(),
        ]);
    }

    /**
     * @Route("/{id}", methods={"PATCH"})
     */

    // метод Patch
    public function patch(Toy $toy, Request $request): JsonResponse
    {
        // json_decode декодирует строку JSON -  преобразует её в PHP-значение
        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $toy->setName($data['name']);
        }

        if (isset($data['price'])) {
            $toy->setPrice($data['price']);
        }

        if (isset($data['description'])) {
            $toy->setDescription($data['description']);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return new JsonResponse([
            'id' => $toy->getId(),
            'name' => $toy->getName(),
            'price' => $toy->getPrice(),
            'description' => $toy->getDescription(),
        ]);
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     */

    // метод Delete
    public function delete(Toy $toy): JsonResponse
    {
        // менеджер сущностей
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($toy);
        // запрос DELETE не выполняется пока не вызван метод flush()
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}

