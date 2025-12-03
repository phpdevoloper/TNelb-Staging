<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Admin\LicenceCategory;
use App\Models\Mst_education;
use App\Models\Mst_experience;
use App\Models\MstLicence;
use App\Models\TnelbApplicantPhoto;
use App\Models\TnelbFormP;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class FormPController extends BaseController
{
	protected $today, $dbNow;
	public function __construct()
	{
		parent::__construct();
		$this->middleware('web');
		$this->today = Carbon::today()->toDateString();
		$this->dbNow  = DB::selectOne("SELECT TO_CHAR(NOW(), 'DD-MM-YYYY') AS db_now")->db_now;
	}

	public function apply_form_p()
	{
		if (!Auth::check()) {
			return redirect()->route('logout');
		}
		$authUser = Auth::user();

		$user = [
			'user_id' => $authUser->login_id,
			'salutation' => $authUser->salutation,
			'applicant_name' => $authUser->first_name . ' ' . $authUser->last_name,
		];

		return view('user_login.apply-form-p', compact('user'));
	}


	public function store(Request $request)
	{
		$request->merge([
			'aadhaar' => preg_replace('/\D/', '', $request->aadhaar)
		]);


		$request->validate([

			// basic fields
			'login_id'             => 'required|string',
			'applicant_name'       => 'required|string|max:255',
			'fathers_name'         => 'required|string|max:255',
			'applicants_address'   => 'required|string|max:500',
			'd_o_b'                => 'required|date',
			'age'                  => 'required|integer|min:18|max:100',
			'previously_number'    => 'nullable|string',
			'previously_date'      => 'nullable|date',
			'wireman_details'      => 'nullable|string|max:255',
			'aadhaar'              => 'required|string|digits:12',
			'pancard'              => 'required|string|size:10',
			'form_name'            => 'required|string|max:2',
			'license_name'         => 'required|string|max:2',
			'form_id'              => 'required|integer',
			// 'amount'               => 'required|numeric|min:0',
			'certificate_no'            => 'nullable|string',
			'certificate_date'              => 'nullable|date',


			// education arrays
			'educational_level'    => 'required|array|min:1',
			'educational_level.*'  => 'required|string|max:50',
			'institute_name'       => 'required|array|min:1',
			'institute_name.*'     => 'required|string|max:255',
			'year_of_passing'      => 'required|array|min:1',
			'year_of_passing.*'    => 'required|digits:4',
			'percentage'           => 'required|array|min:1',
			'percentage.*'         => 'required|numeric|min:0|max:100',

			// work experience arrays
			'work_level'           => 'required|array|min:1',
			'work_level.*'         => 'required|string|max:50',
			'experience'           => 'required|array|min:1',
			'experience.*'         => 'required|integer|min:0|max:50',
			'designation'          => 'required|array|min:1',
			'designation.*'        => 'required|string|max:100',


			// Institute arrays
			'institute_name_address'  => 'required|array|min:1',
			'institute_name_address.*'            => 'required|string|max:255',
			'duration'              => 'required|array|min:1',
			'duration.*'            => 'required|integer|min:0|max:50',
			'from_date'             => 'required|array|min:1',
			'from_date.*'        		=> 'required|date',
			'to_date'        				=> 'required|array|min:1',
			'to_date.*'        			=> 'required|date',

			// single files
			'upload_photo'         => 'required|image|mimes:jpg,jpeg,png|max:50', // 1MB
			'aadhaar_doc'          => 'required|mimes:pdf|min:10|max:250',
			'pancard_doc'          => 'required|mimes:pdf|min:10|max:250',

			// multiple files (arrays)
			'education_document'   => 'required|array|min:1',
			'education_document.*' => 'file|mimes:pdf,jpg,jpeg,png|max:200',
			'work_document'        => 'required|array|min:1',
			'work_document.*'      => 'file|mimes:pdf,jpg,jpeg,png|max:200',
			'institute_document'        => 'required|array|min:1',
			'institute_document.*'      => 'file|mimes:pdf,jpg,jpeg,png|max:200',


		], [

			// education arrays
			'educational_level.required'    => 'Please add at least one educational qualification.',
			'educational_level.*.required'  => 'Educational level is required.',
			'educational_level.*.string'    => 'Educational level must be a valid string.',
			'educational_level.*.max'       => 'Educational level may not be greater than 50 characters.',

			'institute_name.required'       => 'Please add at least one educational qualification.',
			'institute_name.*.required'     => 'Institute name is required.',
			'institute_name.*.string'       => 'Institute name must be a valid string.',
			'institute_name.*.max'          => 'Institute name may not be greater than 255 characters.',

			'year_of_passing.required'      => 'Please add at least one educational qualification.',
			'year_of_passing.*.required'    => 'Year of passing is required.',
			'year_of_passing.*.digits'      => 'Year of passing must be a 4-digit year.',

			'percentage.required'           => 'Please add at least one educational qualification.',
			'percentage.*.required'         => 'Percentage/Grade is required.',
			'percentage.*.numeric'          => 'Percentage/Grade must be a number.',
			'percentage.*.min'              => 'Percentage/Grade must be at least 0.',
			'percentage.*.max'              => 'Percentage/Grade may not exceed 100.',

			// work experience arrays
			'work_level.required'           => 'Please add at least one work experience.',
			'work_level.*.required'         => 'Work level is required.',
			'work_level.*.string'           => 'Work level must be a valid string.',
			'work_level.*.max'              => 'Work level may not be greater than 50 characters.',

			'experience.required'           => 'Please add at least one work experience.',
			'experience.*.required'         => 'Experience (in years) is required.',
			'experience.*.integer'          => 'Experience must be an integer.',
			'experience.*.min'              => 'Experience cannot be negative.',
			'experience.*.max'              => 'Experience may not exceed 50 years.',

			'designation.required'          => 'Please add at least one work experience.',
			'designation.*.required'        => 'Designation is required.',
			'designation.*.string'          => 'Designation must be a valid string.',
			'designation.*.max'             => 'Designation may not be greater than 100 characters.',


			'institute_name_address.required' => 'Institute name and address is required.',
			'institute_name_address.min' => 'At least one institute name and address is required.',
			'institute_name_address.*.required' => 'Each institute name and address is required.',
			'institute_name_address.*.string' => 'Institute name and address must be valid text.',
			'institute_name_address.*.max' => 'Institute name and address may not be greater than 255 characters.',

			'duration.required' => 'Duration field is required.',
			'duration.min' => 'At least one duration entry is required.',
			'duration.*.required' => 'Each duration value is required.',
			'duration.*.integer' => 'Duration must be a valid number.',
			'duration.*.min' => 'Duration must be at least 0 years.',
			'duration.*.max' => 'Duration may not be greater than 50 years.',

			'from_date.required' => 'From date is required.',
			'from_date.min' => 'At least one from date is required.',
			'from_date.*.required' => 'Each from date is required.',
			'from_date.*.date' => 'From date must be a valid date.',

			'to_date.required' => 'To date is required.',
			'to_date.min' => 'At least one to date is required.',
			'to_date.*.required' => 'Each to date is required.',
			'to_date.*.date' => 'To date must be a valid date.',


			'aadhaar.digits' => 'Aadhaar number should be 12 digits.',
			'pancard_doc.min' => 'PAN card file is too small.',

			'education_document.*.max'    => 'Educational document must not be greater than 200 kilobytes.',
			'work_document.*.max'    => 'Experience document must not be greater than 200 kilobytes.',
			'institute_document.*.max'    => 'Institure document must not be greater than 200 kilobytes.',

			'pancard_doc.max' => 'The pancard doc must not be greater than 250 kilobytes.',
		]);


		$action = $request->input('form_action');
		$loginId = $request->login_id;


		DB::beginTransaction();

		$encrypted_aadhaar = Crypt::encryptString($request->aadhaar);
		$encrypted_pancard = Crypt::encryptString($request->pancard);

		try {
			// Generate New Application ID
			$appl_type = $request->appl_type ?? '';
			if ($appl_type == 'R') {
				$lastApplication = TnelbFormP::latest('id')->value('application_id');
				if ($lastApplication) {
					$lastNumber = (int) substr($lastApplication, -7);
					$newApplicationId = $appl_type . $request->form_name . $request->license_name . date('y') . str_pad($lastNumber + 1, 7, '0', STR_PAD_LEFT);
				} else {
					$newApplicationId = $appl_type . $request->form_name . $request->license_name . date('y') . '1111111';
				}
			} else {
				$lastApplication = TnelbFormP::latest('id')->value('application_id');
				if ($lastApplication) {
					$lastNumber = (int) substr($lastApplication, -7);
					$newApplicationId = $request->form_name . $request->license_name . date('y') . str_pad($lastNumber + 1, 7, '0', STR_PAD_LEFT);
				} else {
					$newApplicationId = $request->form_name . $request->license_name . date('y') . '1111111';
				}
			}

			$aadhaarFilename = null;
			$panFilename = null;

			if ($request->hasFile('aadhaar_doc')) {
				$file = $request->file('aadhaar_doc');

				$contents = file_get_contents($file->getRealPath());

				$encrypted = Crypt::encrypt($contents);

				$aadhaarFilename = time() . '_' . rand(10000, 9999999) . '.bin';
				$destinationPath = storage_path('app/private_documents');


				if (!is_dir($destinationPath)) {
					mkdir($destinationPath, 0755, true);
				}

				file_put_contents($destinationPath . '/' . $aadhaarFilename, $encrypted);
			}

			if ($request->hasFile('pancard_doc')) {
				$file = $request->file('pancard_doc');

				$contents = file_get_contents($file->getRealPath());

				$encrypted = Crypt::encrypt($contents);

				$panFilename = time() . '_' . rand(10000, 9999999) . '.bin';

				$destinationPath = storage_path('app/private_documents');

				if (!is_dir($destinationPath)) {
					mkdir($destinationPath, 0755, true);
				}

				file_put_contents($destinationPath . '/' . $panFilename, $encrypted);
			}


			$form = TnelbFormP::create([
				'login_id'            => $loginId,
				'applicant_name'      => $request->applicant_name ?? '',
				'fathers_name'        => $request->fathers_name ?? '',
				'applicants_address'  => $request->applicants_address,
				'd_o_b'               => $request->dob ?? $request->d_o_b,
				'age'                 => $request->age,
				'previously_number'   => $request->previously_number ?? 0,
				'previously_date'     => $request->previously_date ?? 0,
				'application_id'      => $newApplicationId,
				'wireman_details'     => $request->wireman_details,
				'form_name'           => $request->form_name,
				'form_id'             => $request->form_id,
				'license_name'        => $request->license_name,
				'aadhaar'             => $encrypted_aadhaar,
				'pancard'             => $encrypted_pancard,
				'status'              => 'P',
				'appl_type'           => $appl_type,
				'payment_status'      => ($action === 'draft') ? 'draft' : 'payment',
				'aadhaar_doc'         => $aadhaarFilename,
				'pan_doc'             => $panFilename,
				'certificate_no'      => $request->certificate_no,
				'certificate_date'    => $request->certificate_date,
				'cert_verify'         => $request->cert_verify ?? '0',
				'license_verify'      => $request->l_verify ?? '0',
			]);

			$applicationId = $form->application_id;
			$loginId = $form->login_id;


			$form_details = MstLicence::where('status', 1)
				->select('*')
				->get()
				->toArray();
			$form_category = LicenceCategory::where('status', 1)
				->select('*')
				->get()
				->toArray();

			$current_form = collect($form_details)->firstWhere('cert_licence_code', $form->license_name);
			$category_type = collect($form_category)->firstWhere('id', $current_form['category_id']);

			$licence_details['licence_name'] = $current_form['licence_name'];
			// var_dump($licence_details);die;
			$licence_details['category_name'] = $category_type['category_name'];
			$licence_details['form_type'] = $form->appl_type;

			// process education
			if ($request->has('educational_level')) {
				foreach ($request->educational_level as $key => $level) {
					// skip empty rows
					if (empty($level) || empty($request->institute_name[$key])) continue;

					// compute edu_serial safely
					$lastEdu = Mst_education::whereNotNull('edu_serial')->latest('id')->value('edu_serial');
					if ($lastEdu) {
						$lastNum = (int) str_replace('edu_', '', $lastEdu);
						$newEduSerial = 'edu_' . ($lastNum + 1);
					} else {
						$newEduSerial = 'edu_1';
					}

					$filePath = null;
					if ($request->hasFile("education_document") && isset($request->file("education_document")[$key])) {
						$file = $request->file("education_document")[$key];
						$filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
						$destinationPath = public_path('education_document');
						$file->move($destinationPath, $filename);
						$filePath = 'education_document/' . $filename;
					}

					Mst_education::create([
						'login_id'           => $loginId,
						'educational_level'  => $level,
						'institute_name'     => $request->institute_name[$key],
						'year_of_passing'    => $request->year_of_passing[$key],
						'percentage'         => $request->percentage[$key],
						'application_id'     => $newApplicationId,
						'edu_serial'         => $newEduSerial,
						'upload_document'    => $filePath,
					]);
				}
			}

			// process experience
			if ($request->has('work_level')) {
				foreach ($request->work_level as $key => $company) {
					if (empty($company) || empty($request->experience[$key]) || empty($request->designation[$key])) {
						continue;
					}

					// compute exp_serial safely
					$lastExp = Mst_experience::whereNotNull('exp_serial')->latest('id')->value('exp_serial');
					if ($lastExp) {
						$lastNum = (int) str_replace('exp_', '', $lastExp);
						$newExpSerial = 'exp_' . ($lastNum + 1);
					} else {
						$newExpSerial = 'exp_1';
					}

					$filePath = null;
					if ($request->hasFile("work_document") && isset($request->file("work_document")[$key])) {
						$file = $request->file("work_document")[$key];
						$filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
						$destinationPath = public_path('work_experience');
						$file->move($destinationPath, $filename);
						$filePath = 'work_experience/' . $filename;
					}

					Mst_experience::create([
						'login_id'        => $loginId,
						'company_name'    => $company,
						'experience'      => $request->experience[$key],
						'designation'     => $request->designation[$key],
						'application_id'  => $newApplicationId,
						'exp_serial'      => $newExpSerial,
						'upload_document' => $filePath,
					]);
				}
			}

			if ($request->has('institute_name_address')) {
				foreach ($request->institute_name_address as $key => $institute) {
					if (empty($institute) || empty($request->duration[$key]) || empty($request->from_date[$key]) || empty($request->from_date[$key])) {
						continue;
					}

					$lastExp = Mst_experience::whereNotNull('exp_serial')->latest('id')->value('exp_serial');
				}
			}

			// process photo
			if ($request->hasFile('upload_photo')) {
				$photoPath = 'user_' . time() . '.' . $request->file('upload_photo')->getClientOriginalExtension();
				$destinationPath = public_path('attached_documents');
				$request->file('upload_photo')->move($destinationPath, $photoPath);

				TnelbApplicantPhoto::create([
					'login_id'       => $loginId,
					'application_id' => $applicationId,
					'upload_path'    => 'attached_documents/' . $photoPath,
				]);
			}



			DB::commit();

			return response()->json([
				'status' => 'success',
				'message' => 'Form submitted successfully!',
				'application_id' => $applicationId,
				'applicantName' => $form->applicant_name,
				'form_name' => $form->form_name,
				'licence_name' => $licence_details['licence_name'],
				'type_of_apps' => $licence_details['category_name'],
				'form_type' => $licence_details['form_type'] == 'N' ? 'FRESH' : 'RENEWAL',
				'date_apps'      => $this->dbNow
			]);
		} catch (\Exception $e) {
			DB::rollBack();

			return response()->json([
				'status' => 'error',
				'message' => 'Failed to save form: ' . $e->getMessage()
			], 500);
		}
	}
}
