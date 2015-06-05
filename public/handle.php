<?php
require_once '../Lib.php';

$resp = null;
switch(Request::any('request')){
    case 'issue':
        $resp = createIssue(Request::any('description'));
        break;
    case 'issues':
        $resp = allIssues();
        break;
    case 'issue-vote':
        $resp = voteIssue(Request::any('id'));
        break;

}

header('Content-Type: application/json');
echo packageResponse($resp);



function packageResponse($res)
{
    $response = [];
    if($res){
        if(is_array($res) && isset($res['error'])){
            $response['success'] = false;
            $response['error'] = $res['error'];
        }
        else {
            $response['success'] = true;
            $response['data'] = $res;
        }
    }
    else {
        $response['success'] = false;
        $response['error'] = 'Error occured.';
    }

    return json_encode($response);
}


function createIssue($description)
{
    $issue = new Issue();
    $issue->description = $description;
    $issue->votes = 0;
    $issue->insert();
    return $issue;
}

function allIssues()
{
    return Issue::findAll();
}

function voteIssue($id)
{
    $issue = Issue::find($id);
    if(!$issue)
        return ['error' => 'Issue not found'];
    $issue->votes += 1;
    $issue->update();
    return $issue;
}
