    <?php



    /**
     * This function retrieves POST data, sanitizes it,  and saves it in the db.
     * @param Request $request
     * @param InterviewQuestion $interviewquestion
     */
    public function saveInterviewQuestion(Request $request, InterviewQuestion $interviewquestion)
    {

        $user_id = $this->getUserId();
        //get user input        
        $question = $request->input('question');
        $answer = $request->input('answer');

        //sanitize user input
        $question = filter_var($question, FILTER_SANITIZE_STRING);
        $answer = filter_var($answer, FILTER_SANITIZE_STRING);

        //save the data
        $interviewquestion->question = $question;
        $interviewquestion->answer = $answer;
        $interviewquestion->user_id = $user_id;
        $interviewquestion->save();

    }

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
