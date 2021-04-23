<?PHP
//Set our script to JSON
header( 'Content-Type: application/json' );

//Web Login Page
$ServerURL                =    "PUT YOUR URL HERE WITHOUT PROTOTCAL";  //e.g.  "company.3cx.co.uk"

//Web URL Credentials
$LoginCreds                =    new stdClass( );
$LoginCreds->username    =    "WEB USERNAME";  //admin
$LoginCreds->password    =    "WEB PASSWORD";  //Password

//Function to post data to website and read returned cookie
function Get3CXCookie( )
{
    global $ServerURL, $LoginCreds;
    
    $UserDetails    =    json_encode( $LoginCreds );

    $PostData        =    file_get_contents( "https://". $ServerURL ."/api/login", null, stream_context_create( array(
        'http' => array(
                'protocol_version'  =>  '1.1',
                'user_agent'        =>  'PHP',
                'method'            =>  'POST',
                'header'            =>  'Content-type: application/json\r\n'.
                                        'User-Agent: PHP\r\n'.
                                        'Connection: close\r\n' .
                                        'Content-length: ' . strlen( $UserDetails ) . '',
                'content'           =>   $UserDetails,
        ),
    ) ) );
    
    $TempCookie        =    explode( "; ", $http_response_header[9] );
    
    $FinalCookie    =    substr( $TempCookie[0], 12 );
    
    if( $PostData == "AuthSuccess" )
        return $FinalCookie;

    return null;
}

function GetAPIData( $API, $AuthCookie )
{
    global $ServerURL;

    $GetData = file_get_contents( "https://". $ServerURL ."/api/" . $API, null, stream_context_create( array(
        'http' => array(
            'protocol_version'    =>  '1.1',
            'user-agent'        =>    'PHP',
            'method'            =>    'GET',
            'header'            =>    'Cookie: '. $AuthCookie .''
            ),
    ) ) );

    return $GetData;
}

$Auth3CX        =    Get3CXCookie( );

if( strlen( $Auth3CX ) != 0 )
{
    /*======================================================================================================================================*/
    //SystemStatus
    //$DisplaySystemStatusAPI    =    GetAPIData( "SystemStatus", $Auth3CX );
    
    //Output to screen pretty
    //echo json_encode( json_decode( $DisplaySystemStatusAPI ), JSON_PRETTY_PRINT );
    
    //Decode JSON ready for grabbign VARS
    //$SystemStatus                =    json_decode( $DisplaySystemStatusAPI );
    
    //Grab Values
    /*
    echo "FQDN: " . $SystemStatus->FQDN . "\n";
    echo "Webmeeting FQDN: " . $SystemStatus->WebMeetingFQDN . "\n";
    echo "Webmeeting Status: " . $SystemStatus->WebMeetingStatus . "\n";
    echo "Version: " . $SystemStatus->Version . "\n";
    echo "Recoding State: " . $SystemStatus->RecordingState . "\n";
    echo "Activated: " . $SystemStatus->Activated . "\n";
    echo "Max Sim Calls: " . $SystemStatus->MaxSimCalls . "\n";
    echo "Max Sim Meeting: " . $SystemStatus->MaxSimMeetingParticipants . "\n";
    echo "Call History: " . $SystemStatus->CallHistoryCount . "\n";
    echo "Chat Message Count: " . $SystemStatus->ChatMessagesCount . "\n";
    echo "Extensions Registered: " . $SystemStatus->ExtensionsRegistered . "\n";
    echo "Own Push Certificate: " . $SystemStatus->OwnPush . "\n";
    echo "Public IP: " . $SystemStatus->Ip . "\n";
    echo "Local IP Valid: " . $SystemStatus->LocalIpValid . "\n";
    echo "Local IP: " . $SystemStatus->CurrentLocalIp . "\n";
    echo "All Local IP's: " . $SystemStatus->AvailableLocalIps . "\n";
    echo "Total Extensions: " . $SystemStatus->ExtensionsTotal . "\n";
    echo "Has Unregistered Extensions: " . $SystemStatus->HasUnregisteredSystemExtensions . "\n";
    echo "Services Not Running: " . $SystemStatus->HasNotRunningServices . "\n";
    echo "Trunks Registered: " . $SystemStatus->TrunksRegistered . "\n";
    echo "Trunks Total: " . $SystemStatus->TrunksTotal . "\n";
    echo "FQDN: " . $SystemStatus->CallsActive . "\n";
    echo "Total Blacklisted IP's: " . $SystemStatus->BlacklistedIpCount . "\n";
    echo "Mem Usage %: " . $SystemStatus->MemoryUsage . "\n";
    echo "Free Physical Mem: " . $SystemStatus->PhysicalMemoryUsage . "\n";
    echo "Free Virtual Memory: " . $SystemStatus->FreeFirtualMemory . "\n";
    echo "Total Virtual Memory: " . $SystemStatus->TotalVirtualMemory . "\n";
    echo "Free Physical Memory: " . $SystemStatus->FreePhysicalMemory . "\n";
    echo "Total Physical Memory: " . $SystemStatus->TotalPhysicalMemory . "\n";
    echo "Disk Usage %: " . $SystemStatus->DiskUsage . "\n";
    echo "Free Disk Space: " . $SystemStatus->FreeDiskSpace . "\n";
    echo "Total Disk Space: " . $SystemStatus->TotalDiskSpace . "\n";
    echo "CPU Valie At " . $SystemStatus->CpuUsageHistory[0][0] . ": " . $SystemStatus->CpuUsageHistory[0][1] . "\n";
    echo "CPU Valie At " . $SystemStatus->CpuUsageHistory[1][0] . ": " . $SystemStatus->CpuUsageHistory[1][1] . "\n";
    echo "CPU Valie At " . $SystemStatus->CpuUsageHistory[2][0] . ": " . $SystemStatus->CpuUsageHistory[2][1] . "\n";
    echo "CPU Valie At " . $SystemStatus->CpuUsageHistory[3][0] . ": " . $SystemStatus->CpuUsageHistory[3][1] . "\n";
    echo "CPU Valie At " . $SystemStatus->CpuUsageHistory[4][0] . ": " . $SystemStatus->CpuUsageHistory[4][1] . "\n";
    echo "CPU Valie At " . $SystemStatus->CpuUsageHistory[5][0] . ": " . $SystemStatus->CpuUsageHistory[5][1] . "\n";
    echo "CPU Valie At " . $SystemStatus->CpuUsageHistory[6][0] . ": " . $SystemStatus->CpuUsageHistory[6][1] . "\n";
    echo "CPU Valie At " . $SystemStatus->CpuUsageHistory[7][0] . ": " . $SystemStatus->CpuUsageHistory[7][1] . "\n";
    echo "CPU Valie At " . $SystemStatus->CpuUsageHistory[8][0] . ": " . $SystemStatus->CpuUsageHistory[8][1] . "\n";
    echo "CPU Valie At " . $SystemStatus->CpuUsageHistory[9][0] . ": " . $SystemStatus->CpuUsageHistory[9][1] . "\n";
    echo "Maintenance Expires: " . $SystemStatus->MaintenanceExpiresAt . "\n";
    echo "Support: " . $SystemStatus->Support . "\n";
    echo "License Active: " . $SystemStatus->LicenseActive . "\n";
    echo "Expiration Date: " . $SystemStatus->ExpirationDate . "\n";
    echo "Outbound Rules: " . $SystemStatus->OutboundRules . "\n";
    echo "Backup Scheduled: " . $SystemStatus->BackupScheduled . "\n";
    echo "Last Backup Date: " . $SystemStatus->LastBackupDateTime . "\n";
    echo "Reseller: " . $SystemStatus->ResellerName . "\n";
    echo "License Key: " . $SystemStatus->LicenseKey . "\n";
    echo "Product Key: " . $SystemStatus->ProductCode . "\n";
    echo "Is SPLA: " . $SystemStatus->IsSpla . "\n\n\n";
    */    
    /*======================================================================================================================================*/
    //Active Calls
    //$DisplayActiveCallsAPI        =    GetAPIData( "ActiveCalls", $Auth3CX );
    
    //Output to screen pretty
    //echo json_encode( json_decode( $DisplayActiveCallsAPI ), JSON_PRETTY_PRINT );
    /*
    {
        "Now": "2020-12-23T16:16:47.4072367+00:00",
        "list": [
            {
                "Id": 9,
                "Caller": "101 Steve Jenner Home",
                "Callee": "Voicemail Menu",
                "Status": "Talking",
                "LastChangeStatus": "2020-12-23T16:16:44+00:00",
                "EstablishedAt": "2020-12-23T16:16:44+00:00"
            }
        ],
        "licenseRestricted": false
    }
    */
    
    //Decode JSON ready for grabbign VARS
    //$ActiveCalls                    =    json_decode( $DisplayActiveCallsAPI );
    
    //Grab Values
    //echo "System Time Now: " . $ActiveCalls->Now . "\n";
    //foreach( $ActiveCalls->list as $Call )
    //{
    //    echo $Call->Id. " => " .$Call->Caller. " => " .$Call->Callee. " => " .$Call->Status. " => " .$Call->LastChangeStatus. " => " .$Call->EstablishedAt. "\n";
    //}
    //$Restricted = "No";
    //echo "License Restricted?: " . $Restricted  ? "Yes" : "No" ."\n";
    
    /*======================================================================================================================================*/
    //System Services
    //$DisplayServiceListAPI        =    GetAPIData( "ServiceList", $Auth3CX );
    
    //Output to screen pretty
    //echo json_encode( json_decode( $DisplayServiceListAPI ), JSON_PRETTY_PRINT );
    /*
    {
        "Name": "3CXCfgServ01",
        "DisplayName": "3CX PhoneSystem 01 Configuration Server",
        "Status": 4,
        "MemoryUsed": 3719168,
        "CpuUsage": 0,
        "ThreadCount": 19,
        "HandleCount": 16,
        "startStopEnabled": false,
        "restartEnabled": true
    },
    */
    
    //Decode JSON ready for grabbign VARS
    //$Services                        =    json_decode( $DisplayServiceListAPI );
    
    //Grab Values
    /*
    foreach( $Services as $Service )
    {
        echo "Name: ". $Service->Name ."\n";
        echo "Display Name: ". $Service->DisplayName ."\n";
        switch( $Service->Status )
        {
            case 4: { echo "Running\n";            break;    }
            case 3: { echo "Stop Pending\n";    break;    }
            case 2:    { echo "Start Pending\n";    break;    }
            case 1:    { echo "Stopped\n";            break;    }
            default: { echo "Unknown Status\n";    break;    }
        }
        echo "Memory Used: ". $Service->MemoryUsed / 1024 . " KB\n";
        echo "CPU Usage: ". $Service->CpuUsage . "\n";
        echo "Thread Count: ". $Service->ThreadCount . "\n";
        echo "Handle Count: ". $Service->HandleCount . "\n";
        echo $Service->startStopEnabled ? "Start Stop Enabled: Yes\n" : "Start Stop Enabled: No\n";
        echo $Service->restartEnabled ? "Restart Enabled: Yes\n\n" : "Restart Enabled: No\n\n";
    }
    */
    /*======================================================================================================================================*/    
    //Trunk Information
    //$DisplayTrunkListAPI        =    GetAPIData( "TrunkList", $Auth3CX );
    
    //Output to screen pretty
    //echo json_encode( json_decode( $DisplayTrunkListAPI )->list, JSON_PRETTY_PRINT ). "\n\n";
    /*
    {
            "Id": "56",
            "Number": "10001",
            "Name": "Steptech Solutions",
            "Host": "sip.voip-unlimited.net",
            "Type": "Provider",
            "SimCalls": "10",
            "ExternalNumber": "01892277030",
            "IsRegistered": true,
            "RegisterOkTime": "2020-12-23T01:04:54.0000000Z",
            "RegisterSentTime": "2020-12-23T01:04:54.0000000Z",
            "RegisterFailedTime": "",
            "CanBeDeleted": true,
            "IsExpiredProviderRootCertificate": false,
            "ExpiredProviderRootCertificateDate": ""
        },
    */
    
    //Decode JSON ready for grabbign VARS
    //$Trunks                        =    json_decode( $DisplayTrunkListAPI )->list;
    
    //Get Data
    /*
    foreach( $Trunks as $Trunk )
    {
        echo "Trunk ID: ". $Trunk->Id ."\n";
        echo "Trunk Number: ". $Trunk->Number ."\n";
        echo "Trunk Name: ". $Trunk->Name ."\n";
        echo "Trunk Host: ". $Trunk->Host ."\n";
        echo "Trunk Type: ". $Trunk->Type ."\n";
        echo "Trunk SimCalls: ". $Trunk->SimCalls ."\n";
        echo "Trunk ExternalNumber: ". $Trunk->ExternalNumber ."\n";
        echo $Trunk->IsRegistered ? "Trunk Is Registered: Yes\n" : "Trunk Is Registered: No\n";
        echo "Trunk Register Ok Time: ". $Trunk->RegisterOkTime ."\n";
        echo "Trunk Register Sent Time: ". $Trunk->RegisterSentTime ."\n";
        echo "Trunk Register Failed Time: ". $Trunk->RegisterFailedTime ."\n";
        echo $Trunk->CanBeDeleted ? "Trunk Can Be Deleted: Yes\n" : "Trunk Can Be Deleted: No\n";
        echo $Trunk->IsExpiredProviderRootCertificate ? "Trunk Is Expired Provider Root Certificate: Yes\n" : "Trunk Is Expired Provider Root Certificate: No\n";
        echo $Trunk->ExpiredProviderRootCertificateDate ? "Trunk Expired Provider Root Certificate Date: Yes\n\n\n" : "Trunk Expired Provider Root Certificate Date: No\n\n\n";
    }
    */
    /*======================================================================================================================================*/
    //BlackList
    //$DisplayBlackListAPI        =    GetAPIData( "ipBlackList", $Auth3CX );
    
    //Output to screen pretty
    //echo json_encode( json_decode( $DisplayBlackListAPI )->list, JSON_PRETTY_PRINT );
    /*
    {
        "Id": 21,
        "IpAddress": "88.83.103.225",
        "SubnetMask": "",
        "Action": "enum.BlacklistBlockType.Allowed",
        "ExpirationDate": "2039-09-30T17:15:27.0000000Z",
        "IpAddressRange": "88.83.103.225 - 88.83.103.225",
        "CanBeDeleted": true,
        "Description": "Home"
    },
    */
    
    //Decode JSON ready for grabbign VARS
    //Blacklist                =    json_decode( $DisplayBlackListAPI )->list;
    
    //Get Data
    /*
    foreach( $Blacklist as $IP )
    {
        echo "ID: ". $IP->Id ."\n";
        echo "IP Address: ". $IP->IpAddress ."\n";
        echo "Subnet Mask: ". $IP->SubnetMask ."\n";
        $Action = "Denied";
        switch( $IP->Action )
        {
            case "enum.BlacklistBlockType.Allowed":    {    $Action = "Allowed";    break; }
            case "enum.BlacklistBlockType.Denied":    {    $Action = "Denied";        break; }
            default:                                    $Action = "Unknown";    break;
        }
        echo "Action: ". $Action ."\n";
        echo "Expiration Date: ". $IP->ExpirationDate ."\n";
        echo "IP Address Range: ". $IP->IpAddressRange ."\n";
        echo $IP->CanBeDeleted ? "Can Be Deleted: Yes\n" : "Can Be Deleted: No\n";
        echo "Description". $IP->Description ."\n\n\n";
    }
    */
    /*======================================================================================================================================*/
    //User Info
    $DisplayUserInfoAPI        =    GetAPIData( "CurrentUser", $Auth3CX );
    
    //Output to screen pretty
    //echo json_encode( json_decode( $DisplayUserInfoAPI ), JSON_PRETTY_PRINT );
    
    /*
    "name": "jenners",
    "initials": "A",
    "version": "16.0.7.1078",
    "roles": [
        "GlobalExtensionManager",
        "GlobalAdmin",
        "Admin",
        "Trunks",
        "Recordings",
        "Reports"
    ],
    "email": "support@steptechsolutions.co.uk",
    "registrationEmail": "stephen.jenner@netvector.co.uk",
    "consoleEnabled": true,
    "isProfessional": true,
    "IsHostingMode": false,
    "isChatLogEnabled": false,
    "isInstanceManagerEnabled": true,
    "isLabFeaturesEnabled": false,
    "exchangeServicesEnabled": false,
    "callReportsEnabled": true,
    "acquaintance": null
    */
    
    //Decode JSON ready for grabbign VARS
    //$UserInfo                    =    json_decode( $DisplayUserInfoAPI );
    
    //echo "Name: ". $UserInfo->name ."\n";
    //echo "Initials: ". $UserInfo->initials ."\n";
    //echo "Version: ". $UserInfo->version ."\n";
    /*
    $MyRoles = "";
    foreach( $UserInfo->roles as $role )
    {
        switch( $role )
        {
            case "GlobalExtensionManager":    {    $MyRoles .= "Global Extension Manager ";    break;    }
            case "GlobalAdmin":                {    $MyRoles .= "Global Admin ";                break;    }
            case "Admin":                    {    $MyRoles .= "Admin ";                        break;    }
            case "Trunks":                    {    $MyRoles .= "Trunk Admin ";                    break;    }
            case "Recordings":                {    $MyRoles .= "Recording Admin ";                break;    }
            case "Reports":                    {    $MyRoles .= "Report Admin ";                break;    }
        }
    }
    */
    //echo "My Roles: ". $MyRoles ."\n";
    //echo "Admin Email: ". $UserInfo->email ."\n";
    //echo "License Email: ". $UserInfo->registrationEmail ."\n";
    //echo $UserInfo->consoleEnabled ? "Console Enabled: Yes\n" : "Console Enabled: No\n";
    //echo $UserInfo->isProfessional ? "Is Professional: Yes\n" : "Is Professional: No\n";
    //echo $UserInfo->IsHostingMode ? "Is Hosting Mode: Yes\n" : "Is Hosting Mode: No\n";
    //echo $UserInfo->isChatLogEnabled ? "Chat Log: Yes\n" : "Chat Log: No\n";
    //echo $UserInfo->isInstanceManagerEnabled ? "Is Instance Manager: Yes\n" : "Is Instance Manager: No\n";
    //echo $UserInfo->isLabFeaturesEnabled ? "Is Lab Features: Yes\n" : "Is Lab Features: No\n";
    //echo $UserInfo->exchangeServicesEnabled ? "Exchange Services: Yes\n" : "Exchange Services: No\n";
    //echo $UserInfo->callReportsEnabled ? "Call Reports: Yes\n" : "Call Reports: No\n";
    //echo "Acquaintance: ". $UserInfo->acquaintance ."\n\n\n";
    
    /*======================================================================================================================================*/
    //Call Log
    //$DisplayCallLogAPI        =    GetAPIData( "CallLog", $Auth3CX );
    
    //Output to screen pretty
    //echo json_encode( json_decode( $DisplayCallLogAPI ), JSON_PRETTY_PRINT );
    
    /*
    {
        "CallLogRows": [],
        "HasMoreRows": true,
        "IsExportAllowed": true
    }
    */
    
    //Decode JSON ready for grabbign VARS
    //$CallLog                    =    json_decode( $DisplayCallLogAPI );
    /*======================================================================================================================================*/
    //Backup Info
    //$DisplayBackupRestoreAPI    =    GetAPIData( "BackupAndRestoreList", $Auth3CX );
    
    //Output to screen pretty
    //echo json_encode( json_decode( $DisplayBackupRestoreAPI ), JSON_PRETTY_PRINT );
    
    /*
    {
            "Id": -112157339,
            "FileName": "3CXScheduledBackup.1.zip",
            "CreationTime": "2020-12-22T03:00:00.1729722+00:00",
            "Version": "16.0.7.1078",
            "Size": 98256027,
            "IsEncrypted": false,
            "Footprint": 0
        },
    */
    
    //Decode JSON ready for grabbign VARS
    //$BackupList                =    json_decode( $DisplayBackupRestoreAPI );
    
    //Get Data
    /*
    foreach( $BackupList->list as $Backup )
    {
        echo "Id: ". $Backup->Id ."\n";
        echo "FileName: ". $Backup->FileName ."\n";
        echo "Created: ". $Backup->CreationTime ."\n";
        echo "Version: ". $Backup->Version ."\n";
        echo "Size: ". $Backup->Size / 1024 ." KB\n";

        $Encryption = "No";
        switch( $Backup->IsEncrypted )
        {
            case true:    {    $Encryption = "Yes";        break;    }
            case false: {    $Encryption = "No";            break;    }
            default:    {    $Encryption = "Unknown";    break;    }
        }
        
        echo "Footprint: ". $Backup->Footprint ."\n\n";
    }
    */
    /*======================================================================================================================================*/
}

//Blank return, auth failed!
else
{
    die( "Authentication failed" );
}

?>
