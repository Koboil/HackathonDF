<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Patient;
use App\Entity\Status;
use App\Entity\Questions;
use App\Repository\PatientRepository;
use App\Repository\QuestionsRepository;
use App\Repository\StatusRepository;
use App\Service\OpenAIService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\OllamaService;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ConversationController extends AbstractController
{

    public function __construct(
        protected OllamaService $ollamaService,
        protected OpenAIService $openAIService,
    ) {
        $this->ollamaService = $ollamaService;
        $this->openAIService = $openAIService;
    }

    #[Route('/chatbot/{id}/admin', name: 'chatbot_admin')]
    public function chatbotAdmin(Patient $patient, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $content = $request->get('content');

            $question = new Questions();
            $question->setPatient($patient);
            $question->setQuestion($content);

            $entityManager->persist($question);
            $entityManager->flush();
        }

        $conversation = $this->getPatientConversation($entityManager, $patient);

        return $this->render('chatbot_admin.html.twig', [
            'patient' => $patient,
            'conversation' => $conversation,
        ]);
    }

    #[Route('/chatbot/{id}', name: 'chatbot_patient')]
    public function chatbotPatient(Patient $patient, 
    Request $request, 
    EntityManagerInterface $entityManager, 
    QuestionsRepository $questionsRepository, 
    StatusRepository $statusRepository, OllamaService $ollamaService): Response {
        if ($request->isMethod('POST')) {
            $answer = $request->get('content');
            $file = $request->files->get('file'); // Get the file from the request

            $question = $questionsRepository->findOneBy(['patient' => $patient], ['date' => 'DESC']);
            $status = $statusRepository->findOneBy(['patient_id' => $patient]);
            
            if (!$status) {
                $status = new Status();
                $status->setPatientId($patient);
            }
            

            $response = new Message();
            $response->setPatientId($patient);
            $response->setResponse($answer);
            $questionDoctor = $question->getQuestion();
            $response->setQuestionId($question);
            if ($file instanceof UploadedFile) {
                // Handle image file
                $mimeType = $file->getMimeType();
                if (in_array($mimeType, ['image/png', 'image/jpeg', 'image/jpg'])) {
                    $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                    $file->move($this->getParameter('images_directory'), $fileName);
                    $response->setResponse('Image: ' . $fileName);
                    $type = $ollamaService->determineSeverityPicture($fileName);
                } else {
                    $response->setResponse('Invalid file type');
                }
            } else {
                // Handle plain text content
                $type = $ollamaService->determineSeverityText($questionDoctor, $answer);
                $status->setType($type);
                dd($type);
            }
            

            $entityManager->persist($status);
            $entityManager->persist($response);
            $entityManager->flush();
        }

        $conversation = $this->getPatientConversation($entityManager, $patient);

        return $this->render('chatbot_patient.html.twig', [
            'patient' => $patient,
            'conversation' => $conversation,
        ]);
    }

    protected function getPatientConversation(EntityManagerInterface $entityManager, Patient $patient): array
    {
        $conversation = [];
        $messages = $entityManager->getRepository(Message::class)->findBy(['patient_id' => $patient]);
        $questions = $entityManager->getRepository(Questions::class)->findBy(['patient' => $patient]);

        /** @var Message[] $message */
        foreach ($messages as $message) {
            $conversation[] = [
                'content' => $message->getResponse(),
                'date' => $message->getDate(),
                'type' => 'message',
            ];
        }

        /** @var Questions[] $question */
        foreach ($questions as $question) {
            $conversation[] = [
                'content' => $question->getQuestion(),
                'date' => $question->getDate(),
                'type' => 'question',
            ];
        }

        array_multisort(array_column($conversation, 'date'), SORT_ASC, $conversation);
        return $conversation;
    }
}
