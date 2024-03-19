<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::with('answers')->get();
        return response()->json(['questions' => $questions], 200);
    }
    

    public function getByCoefficient($coefficient)
    {
        $questions = Question::where('coefficient', $coefficient)->get();
        return response()->json(['questions' => $questions], 200);
    }

    public function getByDuration($duration)
    {
        $questions = Question::where('duration', $duration)->get();
        return response()->json(['questions' => $questions], 200);
    }

    public function search(Request $request)
    {
        $searchText = $request->input('text');
        $questions = Question::where('text', 'like', "%$searchText%")->get();
        return response()->json(['questions' => $questions], 200);
    }

    public function store(Request $request)
    {
        $questionData = $request->all();
        $question = Question::create($questionData);
        return response()->json(['question' => $question], 201);
    }

    public function storeMultiple(Request $request)
    {
        $questionsData = $request->input('questions');
        $questions = Question::createMany($questionsData);
        return response()->json(['questions' => $questions], 201);
    }
    public function addAnswer(Request $request, $questionId)
    {
        $question = Question::findOrFail($questionId);
        $answersData = $request->all();
        if (!empty($answersData)) {
            $createdAnswers = [];
            foreach ($answersData as $answerData) {
                $answer = $question->answers()->create($answerData);
                $createdAnswers[] = $answer;
            }

            return response()->json(['answers' => $createdAnswers], 201);
        } else {
            return response()->json(['message' => 'No answers provided'], 400);
        }
    }

    public function updateAnswer(Request $request, $questionId, $answerId)
    {
        $question = Question::findOrFail($questionId);
        $answer = $question->answers()->findOrFail($answerId);
        $answer->update($request->all());
        return response()->json(['answer' => $answer], 200);
    }

    public function deleteAnswer($questionId, $answerId)
    {
        $question = Question::findOrFail($questionId);
        $answer = $question->answers()->findOrFail($answerId);
        $answer->delete();
        return response()->json(null, 204);
    }
    public function getDefaultQuestions()
    {
        $coefficients = [1, 2, 3]; // Par exemple, vous pouvez modifier cette liste en fonction de vos besoins

        // Initialisation d'un tableau pour stocker les questions
        $questions = [];

        // Boucle pour récupérer 10 questions pour chaque coefficient
        foreach ($coefficients as $coefficient) {
            // Récupérer 10 questions pour le coefficient actuel
            $questionsForCoefficient = Question::where('coefficient', $coefficient)->inRandomOrder()->take(3)->get();

            // Ajouter les questions récupérées au tableau des questions
            $questions = array_merge($questions, $questionsForCoefficient->toArray());
        }

        // Retourner les questions
        return response()->json(['questions' => $questions], 200);
    }

    public function getStats()
    {
        $totalQuestions = Question::count();
        $totalAnswers = Answer::count();
        $averageAnswersPerQuestion = $totalAnswers / max($totalQuestions, 1);

        return response()->json([
            'total_questions' => $totalQuestions,
            'total_answers' => $totalAnswers,
            'average_answers_per_question' => $averageAnswersPerQuestion,
        ], 200);
    }
    public function getAnswers($questionId)
    {
        $question = Question::findOrFail($questionId);
        $answers = $question->answers;
        return response()->json(['answers' => $answers], 200);
    }

    public function show($id)
    {
        $question = Question::findOrFail($id);
        return response()->json(['question' => $question], 200);
    }


    public function update(Request $request, $id)
    {
        $question = Question::findOrFail($id);
        $question->update($request->all());
        return response()->json(['question' => $question], 200);
    }

    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();
        return response()->json(null, 204);
    }
}
