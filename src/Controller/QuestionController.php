<?php


namespace App\Controller;


use App\Entity\Question;
use App\Service\MarkdownHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class QuestionController extends AbstractController
{
    /**
     * @Route("/",name="app_homepage")
     */
    public function homepage(Environment $twigEnviroment)
    {

        //example of using Twig service directly
        //$html = $twigEnviroment->render('homepage.html.twig');
        //return new Response($html);

        return $this->render('homepage.html.twig');
    }

    /**
     * @Route("/questions/new")
     */
    public function new(EntityManagerInterface $entityManager)
    {
        $question = new Question();
        $question->setName('Missing pants')
            ->setSlug('missing-pants-' . random_int(0, 1000))
            ->setQuestion(<<<EOF
Hi! So... I'm having a *weird* day. Yesterday, I cast a spell
to make my dishes wash themselves. But while I was casting it,
I slipped a little and I think `I also hit my pants with the spell`.
When I woke up this morning, I caught a quick glimpse of my pants
opening the front door and walking out! I've been out all afternoon
(with no pants mind you) searching for them.
Does anyone have a spell to call your pants back?   
EOF
            );
        if (rand(1, 10) > 2) {
            $question->setAskedAt(new \DateTimeImmutable(sprintf('-%d days', rand(1, 100))));
        }

        $entityManager->persist($question);
        $entityManager->flush();

        return new Response(sprintf(
            'Well hallo! The shiny new question is id #%d, slug: %s',
            $question->getId(),
            $question->getSlug()
        ));
    }

    /**
     * @Route("/questions/{slug}",name="app_question_show")
     */
    public function show($slug, MarkdownHelper $markdownHelper)
    {
        $answers = [
            'Make sure your cat is sitting `purrrfectly` still ?',
            'Honestly, I like furry shoes better than MY cat',
            'Maybe... try saying the spell backwards?',
        ];

        $questionText = "I've been turned into a cat, any thoughts on how to turn back? While I'm **adorable**, I don't really care for cat food.";

        $parsedQuestionText = $markdownHelper->parse($questionText);

        return $this->render('questions/show.html.twig', [
            'question' => ucfirst(str_replace('-', ' ', $slug)),
            'questionText' => $parsedQuestionText,
            'answers' => $answers
        ]);

    }

}