<?php

namespace App\Http\Controllers\Api\V1\Module12_ContainerSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\KubernetesScanService;

class KubernetesScanController extends Controller
{
    protected $k8sService;

    public function __construct(KubernetesScanService $k8sService)
    {
        $this->k8sService = $k8sService;
    }

    /**
     * Quét cluster
     */
    public function scanCluster(Request $request)
    {
        $result = $this->k8sService->scanCluster();

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Kube-bench results
     */
    public function kubeBench()
    {
        $results = $this->k8sService->runKubeBench();

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }

    /**
     * RBAC security
     */
    public function rbacSecurity()
    {
        $rbac = $this->k8sService->checkRbacSecurity();

        return response()->json([
            'success' => true,
            'data' => $rbac
        ]);
    }

    /**
     * Pod security
     */
    public function podSecurity()
    {
        $pods = $this->k8sService->checkPodSecurity();

        return response()->json([
            'success' => true,
            'data' => $pods
        ]);
    }

    /**
     * Network policies
     */
    public function networkPolicies()
    {
        $policies = $this->k8sService->getNetworkPolicies();

        return response()->json([
            'success' => true,
            'data' => $policies
        ]);
    }

    /**
     * Secrets security
     */
    public function secretsSecurity()
    {
        $secrets = $this->k8sService->checkSecretsSecurity();

        return response()->json([
            'success' => true,
            'data' => $secrets
        ]);
    }

    /**
     * Admission controllers
     */
    public function admissionControllers()
    {
        $controllers = $this->k8sService->getAdmissionControllers();

        return response()->json([
            'success' => true,
            'data' => $controllers
        ]);
    }
}