<?php

function token(){
    return 'TOKEN HERE';
}


// use a specific tag to snapshot a droplet
function tagName(){
    return 'TAG NAME HERE';
}

// list all droplets with a specified tag @ tagName()
function listDroplets()
{
    $ch = curl_init('https://api.digitalocean.com/v2/droplets?tag_name='.tagName());
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . token(),
        'Content-Type: application/json',
    ]);
    $results = json_decode(curl_exec($ch));
    return $results;
}

// list all snapshots with type droplet
function listSnapshots(){
    $ch = curl_init('https://api.digitalocean.com/v2/snapshots?resource_type=droplet');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . token(),
        'Content-Type: application/json',
    ]);
    $results = json_decode(curl_exec($ch));
    return $results;
}

// snap a droplet by id
function snap($droplet_id){
    $data = json_encode(['type' => 'snapshot']);
    $ch = curl_init('https://api.digitalocean.com/v2/droplets/'.$droplet_id.'/actions');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer '.token(),
        'Content-Type: application/json',
    ]);
    $results = json_decode(curl_exec($ch));
    return $results;
}

// delete a snap by id
function deleteSnap($snap_id){
    $ch = curl_init('https://api.digitalocean.com/v2/snapshots/'.$snap_id);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer '.token(),
        'Content-Type: application/json',
    ]);
    $results = json_decode(curl_exec($ch));
    return $results;
}

$droplets = listDroplets();

$snaps = listSnapshots();

// loop throw snapshots and check if they are older than 7 days and not Friday, they will be deleted.
if($snaps){
    foreach ($snaps->snapshots as $snap){
        $snap_day = date('D', strtotime($snap->created_at));
        $today = new DateTime(date('Y-m-d'));
        $snap_date = new DateTime($snap->created_at);
        $diff = $snap_date->diff($today)->format("%a");
        if($snap_day != 'Fri' || ($diff >= 7 && $snap_day == 'Fri')){
            echo "Snapshot found: \n name is: $snap->name \n id is: $snap->id \n date is: $snap->created_at \n Now deleting it... \n\n\n ########################## \n";
            deleteSnap($snap->id);
        }
    }
}


// now find droplets and back them up.
foreach($droplets->droplets as $droplet){
    echo "\n \n Droplet: $droplet->name is being backed up now \n \n";
    snap($droplet->id);
}
