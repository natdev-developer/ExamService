<?php
/**
 * Created by PhpStorm.
 * User: Asus
 * Date: 23.11.2019
 * Time: 15:51
 */

namespace App\Controller\Admin;


use App\Entity\Admin\Answer;
use App\Form\Admin\AnswerType;
use App\Repository\Admin\AnswerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController extends AbstractController
{
    /**
     * @Route("answer/{examId}/{questionId}", name="answer")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Answer::class);
        $question = new Answer([]);

        $examId = $request->attributes->get('examId');
        $questionId = $request->attributes->get('questionId');

        $form = $this->createForm(AnswerType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data[0] = $request->request->get('content');
            $data[1] = $request->request->get('is_true');
            $data[2] = $request->request->get('is_active');

            $answer = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $values = $answer->getAllInformation();
            $examValue = $request->attributes->get('examId');
            $questionValue = $request->attributes->get('questionId');

            $repositoryAnswer = new AnswerRepository();
            $repositoryAnswer->insert($examValue, $questionValue, $values);

            return $this->redirectToRoute('answerList', [
                'examId' => $examId,
                'questionId' => $questionId
            ]);
        }

        return $this->render('answerAdd.html.twig', [
            'form' => $form->createView(),
            'title' => 'Answers to question ',
            'examId' => $examId,
            'questionId' => $questionId
        ]);
    }

    /**
     * @Route("answerList/{questionId}/{examId}", name="answerList")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function answerListCreate(Request $request) {
        $answerInformation= new AnswerRepository();

        $_SESSION['exam_id'] = "";
        $_SESSION['question_id'] = "";

        $examId = $request->attributes->get('examId');
        $questionId = $request->attributes->get('questionId');

        $answersId = $answerInformation->getIdAnswers($examId,$questionId);
        if($answersId!=0){
            $answersCount = count($answersId);
        } else {
            $answersCount=0;
        }
        if($answersCount>0) {
            $info=true;
            for ($i = 0; $i < $answersCount; $i++) {
                $answers = $answerInformation->getAnswer($examId,$questionId,$answersId[$i]);
                if ($answers['is_true'] == 1) {
                    $is_required = "true";
                } else {
                    $is_required = "false";
                }
                if ($answers['is_active'] == 1) {
                    $is_required_active = "true";
                } else {
                    $is_required_active = "false";
                }

                $tplArray[$i] = array(
                    'id' => $i,
                    'content' => $answers['content'],
                    'is_true' => $is_required,
                    'is_active' => $is_required_active
                );
            }
        } else {
            $info=false;
            $tplArray = array(
                'id' => 0,
                'content' => 0,
                'is_true' => 0,
                'is_active' => 0
            );
        }
        return $this->render( 'answerList.html.twig', array (
            'data' => $tplArray,
            'examId' => $examId,
            'questionId' => $questionId,
            'information' => $info
        ) );
    }
    /**
     * @param Request $request
     * @param Answer $answer

     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("editAnswer/{examId}/{questionId}/{id}", name="editAnswer")
     */
    public function editExam(Request $request, Answer $answer)
    {
        $examId = (int)$request->attributes->get('examId');
        $questionId = (int)$request->attributes->get('questionId');
        $id = (int)$request->attributes->get('id');
        $_SESSION['exam_id'] = $examId;
        $_SESSION['question_id'] = $questionId;

        $answerInformation = new AnswerRepository();
        $answers = $answerInformation->getAnswer($examId,$questionId,$id);

        $examInfoArray = array(
            'content' => $answers['content'],
            'is_active' => $answers['is_active'],
            'is_true' => $answers['is_true'],

        );

        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $exams = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();

            $values = $answer->getAllInformation();
            $answerInformation->update($values,$examId,$questionId,$id);
            return $this->redirectToRoute('answerList', [
                'examId' => $examId,
                'questionId' => $questionId
            ]);
        }
        return $this->render('answerAdd.html.twig', [
            'form' => $form->createView(),
            'examInformation' =>$examInfoArray,
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("deleteAnswer/{exam}/{question}/{answer}", name="deleteAnswer")
     */
    public function deleteAnswer(Request $request)
    {
        $examId = $request->attributes->get('exam');
        $questionId = $request->attributes->get('question');
        $answerId = $request->attributes->get('answer');
        $repo = new AnswerRepository();

        $repo->delete($examId,$questionId, $answerId);

        return $this->redirectToRoute('answerList', [
            'examId' => $examId,
            'questionId' => $questionId
        ]);
    }
}
