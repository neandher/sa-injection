<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Video;
use App\Form\VideoType;
use Doctrine\ORM\NonUniqueResultException as NonUniqueResultExceptionAlias;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @Route("/home", name="home")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        /** @var Post[] $posts */
        $posts = $postRepository->findAllPosts($request->query->get('s'));

        return $this->render('home/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/post/{id}", name="post_view")
     * @param $id
     * @return Response
     * @throws NonUniqueResultExceptionAlias
     */
    public function postView($id)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->findOneById($id);
        return $this->render('home/post_view.html.twig', [
            'post' => $post
        ]);
    }

    /**
     * @Route("/send-video", name="send_video")
     * @param Request $request
     * @return Response
     */
    public function sendVideo(Request $request)
    {
        $video = new Video();
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('videoFile')->getData();
            $fileName = md5(uniqid()) . '.' . ($file->guessExtension() ? $file->guessExtension() : $file->getClientOriginalExtension());
            try {
                $file->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads/videos',
                    $fileName
                );
            } catch (FileException $e) {
            }
            $video->setVideo($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($video);
            $em->flush();

            return $this->redirectToRoute('send_video_success');
        }
        return $this->render('home/send_video.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/send-video-success", name="send_video_success")
     * @return Response
     */
    public function sendVideoSuccess()
    {
        $videoRepository = $this->getDoctrine()->getRepository(Video::class);
        $videos = $videoRepository->findAll();

        return $this->render('home/send_video_success.html.twig', [
            'videos' => $videos
        ]);
    }
}
