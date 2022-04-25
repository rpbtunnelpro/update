<?php
  function pushFile($token,$repo,$branch,$path,$b64data){
    $message = "Automated update";
    $ch = curl_init("https://api.github.com/repos/$repo/branches/$branch");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent:Php/Automated'));
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $token);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $data = curl_exec($ch);
    curl_close($ch);
    $data=json_decode($data,1);

    $ch2 = curl_init($data['commit']['commit']['tree']['url']);
    curl_setopt($ch2, CURLOPT_HTTPHEADER, array('User-Agent:Php/Ayan Dhara'));
    curl_setopt($ch2, CURLOPT_USERPWD, $username . ":" . $token);
    curl_setopt($ch2, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, TRUE);
    $data2 = curl_exec($ch2);
    curl_close($ch2);
    $data2=json_decode($data2,1);

    $sha='';
    foreach($data2["tree"] as $file)
      if($file["path"]==$path)
        $sha=$file["sha"];
    
    $inputdata =[];
    $inputdata["path"]=$path;
    $inputdata["branch"]=$branch;
    $inputdata["message"]=$message;
    $inputdata["content"]=$b64data;
    $inputdata["sha"]=$sha;

    echo json_encode($inputdata);

    $updateUrl="https://api.github.com/repos/$repo/contents/$path";
    echo $updateUrl;
    $ch3 = curl_init($updateUrl);
    curl_setopt($ch3, CURLOPT_HTTPHEADER, array('Content-Type: application/xml', 'User-Agent:Php/Ayan Dhara'));
    curl_setopt($ch3, CURLOPT_USERPWD, $username . ":" . $token);
    curl_setopt($ch3, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch3, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch3, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch3, CURLOPT_POSTFIELDS, json_encode($inputdata));
    $data3 = curl_exec($ch3);
    curl_close($ch3);

    echo $data3;
  }
  
  //Get the input request parameters
  $inputJSON = file_get_contents('php://input');
  $input = json_decode($inputJSON, TRUE); //convert JSON into array
  
  $repopassword = $input['repopassword'];
  $repopath = $input['repopath'];
  $filename = $input['filename'];
  $data = $input['data'];
 
   //pushFile("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","romzvpnpro/config-update","main","update.json","VGVzdA==");
   pushFile($repopassword,$repopath,"main",$filename,$data);
?>
