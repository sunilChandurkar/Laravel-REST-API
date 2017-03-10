<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Response;

use App\User;

use App\InterviewQuestion;

use App\Transformers\InterviewQuestionTransformer;

use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    /**
     * @var InterviewQuestionTransformer
     *
     */
    protected $myTransformer;

    public function __construct(InterviewQuestionTransformer $myTransformer){
        $this->myTransformer = $myTransformer;
        }

    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit') ? : 3;

        $interviewquestions = InterviewQuestion::paginate($limit);
        
        return Response::json([
            'data'=>$this->myTransformer->transformCollection($interviewquestions->all()),
            'paginator'=>[
                            'total_count'=>$interviewquestions->total(),
                            'total_pages'=>ceil($interviewquestions->total()/$interviewquestions->perPage()),
                            'current_page'=>$interviewquestions->currentPage(),
                            'limit'=>$interviewquestions->perPage()                    
                         ]
            ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$this->checkInput($request)){
        return Response::json(['error' => ['message' => 'Validation failed. Please send question and answer.']]);
        }
        
        //create the interview question
        $interviewquestion = new InterviewQuestion;

        //save the data
        $this->saveInterviewQuestion($request, $interviewquestion);

        return Response::json(['success' => ['message' => 'Question and Answer added to db.']]);
        
    }

   

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $interviewquestion = $this->getInterviewQuestion($id);
        
        if(! $interviewquestion){
            return Response::json(['error'=>['message'=>'Lesson does not exist.']]);
        }

        return Response::json(['data'=>$this->myTransformer->transform($interviewquestion)]);

    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!$this->checkInput($request)){
        return Response::json(['error' => ['message' => 'Validation failed. Please send question and answer.']]);
        }

        //find the InterviewQuestion
        $interviewquestion = $this->getInterviewQuestion($id);
        if(! $interviewquestion){
           return Response::json(['error'=>['message'=>'InterviewQuestion does not exist.']]);
        }
                
        $this->saveInterviewQuestion($request, $interviewquestion);

        return Response::json(['success' => ['message' => 'Question and Answer updated.']]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $interviewquestion = $this->getInterviewQuestion($id);
        if(! $interviewquestion){
           return Response::json(['error'=>['message'=>'InterviewQuestion does not exist.']]);
        }
        $interviewquestion->delete();
        return Response::json(['success' => ['message' => 'Question and Answer deleted.']]);
    }

    /**
     * This function retrieves POST data, sanitizes it,  and saves it in the db.
     * @param \Illuminate\Http\Request  $request
     * @param InterviewQuestion $interviewquestion
     */

    public function saveInterviewQuestion(Request $request, InterviewQuestion $interviewquestion)
    {
        $user_id = $this->getUserId();

        //get user input        
        $question = (string)$request->input('question');
        $answer = (string)$request->input('answer');

        $question = filter_var($question, FILTER_SANITIZE_STRING);
        $answer = filter_var($answer, FILTER_SANITIZE_STRING);

        //save the data
        $interviewquestion->question = $question;
        $interviewquestion->answer = $answer;
        $interviewquestion->user_id = $user_id;
        $interviewquestion->save();
    }

    /**
     * Returns User Id of authenticated User.
     * @return int $user_id
     */
    public function getUserId(){

        $user = Auth::user();
        
        $user_id = $user->id;

        return $user_id;
    }

    /**
     * FInds and Returns InterviewQuestion $interviewquestion by id.
     * @param int $id
     * @return InterviewQuestion $interviewquestion
     */
    public function getInterviewQuestion($id){
        $interviewquestion = InterviewQuestion::find($id);
        return $interviewquestion;
    }

    /**
     * Checks User Input
     * @param \Illuminate\Http\Request  $request
     * @return boolean
     */

    public function checkInput(Request $request){
        if( ! $request->input('question') or ! $request->input('answer'))
        {
        return false;
        }
        return true;
    }
}