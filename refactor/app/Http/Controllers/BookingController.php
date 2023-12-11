<?php

namespace DTApi\Http\Controllers;

use DTApi\Models\Job;
use DTApi\Http\Requests;
use DTApi\Models\Distance;
use Illuminate\Http\Request;
use DTApi\Repository\BookingRepository;
use DTAPI\HTTP\Request\StoreJobRequest;
use DTAPI\HTTP\Request\UpdateDistanceFeedRequest;


/**
 * Class BookingController
 * @package DTApi\Http\Controllers
 */
class BookingController extends Controller
{
    /**
     * BookingController constructor.
     * @param BookingRepository $bookingRepository
     */
    public function __construct(protected BookingRepository $bookingRepository){}

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $userType = auth()->user()->user_type; 
        $userId = $request->get('user_id');
        if ($userId) {
            return $this->repository->getUsersJobs($userId);
        } elseif ($this->isAdminOrSuperAdmin($userType)) {
            return  $this->repository->getAll($request);
        } 
        abort(403, 'Unauthorized action.');
    }

    /**
     * Check if the user type is admin or superadmin.
     *
     * @param string|null $userType
     * @return bool
     */
    private function isAdminOrSuperAdmin(?string $userType): bool
    {
        return in_array($userType, [config('app.admin_role_id'), config('app.super_admin_role_id')]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $job = $this->repository
                ->with('translatorJobRel.user')
                ->findOrFail($id);
        return response($job);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(StoreJobRequest $request)
    {
        $data = $request->validated();// instead of using all use request validated

        $response = $this->repository->store(auth()->user(), $data);

        return response($response);

    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request)
    {
        $user = auth()->user();
        $data = $request->except(['_token', 'submit']);
        $response = $this->repository->updateJob($id, $data, $user);
        return response($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function immediateJobEmail(Request $request):Response
    {
        $adminSenderEmail = config('app.adminemail');
        $data = $request->all(); // use validated request where possible

        $response = $this->repository->storeJobEmail($data);

        return response($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getHistory(Request $request)
    {
        $user_id = $request->get('user_id');
    
        if(!$user_id) {
            abort(400,'User ID is missing.' );
        }
    
        $response = $this->repository->getUsersJobsHistory($user_id, $request);
    
        return response($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function acceptJob(Request $request)
    {
        $job_id = $this->validate($request,[
            'job_id'=>'required',
        ]);
        $user = auth()->user();
        $response = $this->repository->acceptJob($job_id, $user);

        return response($response);
    }

    public function acceptJobWithId(Request $request)
    {
        $job_id = $this->validate($request,[
            'job_id'=>'required',
        ]);
        $user = auth()->user();

        $response = $this->repository->acceptJobWithId($jobId, $user);

        return response($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function cancelJob(Request $request)
    {
        $validated_req = $this->validate($request,[
            'job_id' => 'required',
        ]);
        $user = auth()->user();

        $response = $this->repository->cancelJobAjax($validated_req->job_id, $user);

        return response($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function endJob(Request $request)
    {
        $data =  $this->validate($request,[
            'job_id' => 'required',
            'user_id' => 'required',
        ]);
        $response = $this->repository->endJob($data);

        return response($response);

    }

    public function customerNotCall(Request $request)
    {
        $job_id = $this->validate($request,[
            'job_id' => 'required',
        ]);

        $response = $this->repository->customerNotCall($job_id);

        return response($response);

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getPotentialJobs()
    {
        $user = auth()->user();

        $response = $this->repository->getPotentialJobs($user);

        return response($response);
    }

    public function distanceFeed(UpdateDistanceFeedRequest $request)
    {
        $data = $request->validated();
        $response = $this->repository->distanceFeed($data);
        return response('Record updated!');
    }
    public function reopen(Request $request)
    {
        $data =  $this->validate($request,[
            'job_id' => 'required',
            'user_id' => 'required',
        ]);
        $response = $this->repository->reopen($data);

        return response($response);
    }

    public function resendNotifications(Request $request)
    {
        $data = $request->all();
        $job = $this->repository->find($data['jobid']);
        $job_data = $this->repository->jobToData($job);
        $this->repository->sendNotificationTranslator($job, $job_data, '*');

        return response(['success' => 'Push sent']);
    }

    /**
     * Sends SMS to Translator
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function resendSMSNotifications(Request $request)
    {
        try {
            $job = $this->repository->findOrFail($request->input('jobid'));
            $job_data = $this->repository->jobToData($job);
            $this->repository->sendSMSNotificationToTranslator($job);
    
            return response(['success' => 'SMS sent'], 200);
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], 500);
        }
    }

}
