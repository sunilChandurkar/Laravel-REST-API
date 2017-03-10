<?php
namespace App\Transformers;

use App\InterviewQuestion;

class InterviewQuestionTransformer
{

/**
 * This function takes an InterviewQuestion Object and returns an array of data.
 * @param InterviewQuestion $interviewquestion
 * @return array of data
 */
    public function transform(InterviewQuestion $interviewquestion){
        
            return [
            		'id'=>$interviewquestion['id'],
                    'question'=>$interviewquestion['question'],
                    'answer'=>$interviewquestion['answer'],
                    'created_by'=>$interviewquestion->user->name
            ];
        
    }

/**
 * This function takes an array of InterviewQuestion objects and returns an array of data.
 * @param array of InterviewQuestion objects
 * @return array of data
 */
    public function transformCollection(array $interviewquestions){
        return array_map([$this, 'transform'], $interviewquestions);
    }

}