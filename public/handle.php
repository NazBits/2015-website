<?php
session_start();
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
    case 'avs':
        $resp = allAVs();
        break;
    case 'av-vote':
        $resp = voteAV(Request::any('id'));
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
    if(!$description)
        return ['error'=>'Please explain your issue.'];
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
    if(Session::issueVoted($id))
        return ['error' => 'You have already said that.'];
    $issue = Issue::find($id);
    if(!$issue)
        return ['error' => 'Issue not found'];
    $issue->votes += 1;
    $issue->update();
    Session::saveIssueVote($id);
    return $issue;
}

function allAVs()
{
    return Antivirus::findAll();
}

function voteAV($id)
{
    if(Session::avBooked($id))
        return ['error' => 'You have already booked this antivirus.'];
    $av = Antivirus::find($id);
    if(!$av)
        return ['error' => 'Antivirus not found'];
    $av->votes += 1;
    $av->update();
    Session::saveAVBook($id);
    return $av;
}
