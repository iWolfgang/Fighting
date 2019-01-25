<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use app\Packages\alipay;
use DB;
Class PaySelectController extends Controller{
	public function index(Request $request)
	{
		$aop = new \AopClient ();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = '2019011062842792';
        $aop->rsaPrivateKey = 'MIIEowIBAAKCAQEA0eaUU8GLz93lCunIq+1mECFpABRPFE32+4Gri2PLRGI9ndbrQLpsQhqZNfxnJ1/cvI2kLNDs3/ZUkB/yi4OjRtHoCeRnzVZYnnsszweCxckPFr4BauSgsTGygpVrVbo2dIzBsBW2EWBHVi/ohWijEDs7kMLpsIFKJ4ug1qq5e5DGNHrv+cW/k+Goor6pWPoj6R8sjhfPuZYrN+JzgFnstvEBGL7yR2MrgKj84vzbgVLaX+CqRGe87fI9JquPhuNGB+cWoxuyqK2YSMzmXDTP+Tym/kDZytRtSODmZli6Ksny+MCMOr1qpuMPQ6bc0tTkKes6oFdO4fryvkRWWU/x3wIDAQABAoIBABN8WGC+IwCVFOJCecKcM1FkCJ9dQ0obQsZub0JtbT1X8Whpv0UvCUXJuldsqxbYq2FFtOwEKTlRYOBQVu/ktI+qhOQGNCy3y1pLDQnbJKS/2Yq+8Nq/hrtsZaoBvQkkVFHVj1WNbm2GhpjVsbxQznJ/TTRPI+qi1gN9ztye1MFHsDMsf4E5gWOnuiBoLZjDXRvit05Ow2XUbjHzqhtudErLFc8ZuokUuZC/Iz1w8VHVnVPK5v3kj0jcPwnDhkK8kRk3sa8nXLGQKO8H/eKSMPtFuXtOmNFU6+aIt7F3S6YzSq7Tz4Fta9x18My/WlmWqmRoGKFUUW+giDcJ3JYy8uECgYEA+ptyA6IfBZxW493clEyUS1DulAKpHuflI6FYy09yS/Vn7kL7++vhzvIruq86f+tpNOT/QnArH4zpE+z3zP9dGPoeDphtlS9tEqb/2M7BXgpZx9qJZHcERDJBecPp3bFabMo7rKiEMorh/eMZAtSzpCFX2Fi62WWcmD93DSDNJBsCgYEA1mrjchOchJBSJDIFAacz8ou+ArSU00w6icKOiRd3YGixnGu+2qLmZ5po5MP+VBAmnCnp30vBh8aL8UMFDYAq9W+1YqQil6DE4I27ra0XD78j+duCGUPAHoH6jglQLov+jP5HAVl2xDuSGSy1Ri02y2741NbNqFFd2u8lU/10HY0CgYBzqWCSqrVUmpZDrrbKPxnGNQEXkK7LU82ehy37D5y5z/Z6sbGo0HI0V/K0w4DlXxn8TqA84pYUhq1gA+NOWqF2EKHkrJcO3oehry+vuaTnKTHMmmEE3CU88FDlyPTb26nXQfMOuevhg9XPnouBkfejDbyEXldGVK5UWh4xEe179wKBgD6CVuCQ+xZihK/srSz4M9rIBpL/Vkvrcz1qLOemobTHkNALUU6oIwedKmtXADQ9qSPpzDa+/SK6LV4ercBr1xpKgNTLCRKvWfYlG8vcJFcA4FodNmZrK/0443S5HlkTkxhDoSuxi0BWJZeVQxu8XrccGQrjvH0Pi48iHP3JbCqZAoGBAOUIvrezFowg8ftSHj68h7138fnw3lS+C37GmQwv96ifl+WLD6RuBZNwauImutBS8LSrU+R4X86VB9yI89PXRUnwm/ZDdKxjtlIcWRbKdqcp6EyjU7NstHNc3CkpA7mEgVQeKJ2am2byCq8WQukYxGU1c5HQwJmveZkAc4hutKEW';;
        $aop->alipayrsaPublicKey='MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjHrWWgUm0nmDbxf/0zobPwRFWZaEpWPo1OqxAa5RhwgAfnvZn+AdFIUIXFEUjsV0PfoOrNSyFuM2zSusMtJWer1xX6rUDbhrRpnaxuibyCJUPWr4zZ4WChRGpgIYp3+7NJeRHkWM24cbkKvdPl4432kpmlh29A0armcKEke5PwzTaV+T+r2VduVGAIezHL0w0APdJFOgsqG+JnO+7rS08y86OOFTYZ2V3xJma5qg4E1UmtCmCQBqC9+MFKgrMzVeQqySw4I6WAFCfe7z5SoioeeFXCuFTekLC8XPmcTZH6wByWzF1nDBzlW2oEEUy5bGhj19d91LaCls5TOTEVJ7SwIDAQAB';
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset='UTF-8';
        $aop->format='json';
        $request = new \AlipayTradeQueryRequest ();
        $request->setBizContent("{" .
        "\"out_trade_no\":\"20190122173359543488\"" .
        // "\"trade_no\":\"2019012222001445631010902265\"" .
        "  }");
        $result = $aop->execute ( $request); 

        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        if(!empty($resultCode)&&$resultCode == 10000){
        echo "成功";
        } else {
        echo "失败";
        }
        die;
	}
}