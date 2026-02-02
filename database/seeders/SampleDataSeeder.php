<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Modules\Employee\Models\Employee;
use App\Modules\Employee\Models\Department;
use App\Modules\Employee\Models\Designation;
use App\Modules\Employee\Models\Location;
use App\Modules\Attendance\Models\Attendance;
use App\Modules\Shift\Models\Shift;
use App\Modules\Leave\Models\Leave;
use App\Modules\Leave\Models\LeaveType;
use App\Modules\Payroll\Models\Payroll;
use App\Modules\Payroll\Models\SalaryStructure;
use App\Modules\Employee\Models\EmployeeTask;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing departments, designations, and locations
        $departments = Department::all();
        $designations = Designation::all();
        $locations = Location::all();
        
        if ($departments->isEmpty() || $designations->isEmpty() || $locations->isEmpty()) {
            $this->command->warn('Please run DatabaseSeeder first to create departments, designations, and locations.');
            return;
        }

        // Create a shift
        $shift = Shift::firstOrCreate(
            ['name' => 'General Shift'],
            [
                'uuid' => (string) Str::uuid(),
                'start_time' => Carbon::now()->setTime(9, 0, 0),
                'end_time' => Carbon::now()->setTime(18, 0, 0),
                'break_duration' => 60, // 1 hour break
                'working_hours' => 8,
                'is_flexible' => false,
                'is_active' => true,
            ]
        );

        // Create leave types
        $leaveTypes = [
            ['name' => 'Casual Leave', 'code' => 'CL', 'max_days_per_year' => 12, 'is_paid' => true],
            ['name' => 'Sick Leave', 'code' => 'SL', 'max_days_per_year' => 10, 'is_paid' => true],
            ['name' => 'Earned Leave', 'code' => 'EL', 'max_days_per_year' => 15, 'is_paid' => true],
        ];

        foreach ($leaveTypes as $lt) {
            LeaveType::firstOrCreate(
                ['code' => $lt['code']],
                array_merge($lt, [
                    'uuid' => (string) Str::uuid(),
                    'is_active' => true,
                ])
            );
        }

        // Note: Salary structures are per-employee, so we'll create them for each employee

        // Sample employee data
        $employeesData = [
            [
                'first_name' => 'Rajesh', 'last_name' => 'Kumar', 'email' => 'rajesh.kumar@hrms.com',
                'phone' => '9876543210', 'gender' => 'Male', 'employee_id' => 'EMP001',
                'ctc' => 600000, 'employment_type' => 'full_time', 'employment_status' => 'active',
            ],
            [
                'first_name' => 'Priya', 'last_name' => 'Sharma', 'email' => 'priya.sharma@hrms.com',
                'phone' => '9876543211', 'gender' => 'Female', 'employee_id' => 'EMP002',
                'ctc' => 550000, 'employment_type' => 'full_time', 'employment_status' => 'active',
            ],
            [
                'first_name' => 'Amit', 'last_name' => 'Patel', 'email' => 'amit.patel@hrms.com',
                'phone' => '9876543212', 'gender' => 'Male', 'employee_id' => 'EMP003',
                'ctc' => 700000, 'employment_type' => 'full_time', 'employment_status' => 'active',
            ],
            [
                'first_name' => 'Sneha', 'last_name' => 'Singh', 'email' => 'sneha.singh@hrms.com',
                'phone' => '9876543213', 'gender' => 'Female', 'employee_id' => 'EMP004',
                'ctc' => 650000, 'employment_type' => 'full_time', 'employment_status' => 'active',
            ],
            [
                'first_name' => 'Vikram', 'last_name' => 'Reddy', 'email' => 'vikram.reddy@hrms.com',
                'phone' => '9876543214', 'gender' => 'Male', 'employee_id' => 'EMP005',
                'ctc' => 800000, 'employment_type' => 'full_time', 'employment_status' => 'active',
            ],
            [
                'first_name' => 'Anjali', 'last_name' => 'Mehta', 'email' => 'anjali.mehta@hrms.com',
                'phone' => '9876543215', 'gender' => 'Female', 'employee_id' => 'EMP006',
                'ctc' => 580000, 'employment_type' => 'full_time', 'employment_status' => 'active',
            ],
            [
                'first_name' => 'Rahul', 'last_name' => 'Verma', 'email' => 'rahul.verma@hrms.com',
                'phone' => '9876543216', 'gender' => 'Male', 'employee_id' => 'EMP007',
                'ctc' => 620000, 'employment_type' => 'full_time', 'employment_status' => 'active',
            ],
            [
                'first_name' => 'Kavita', 'last_name' => 'Joshi', 'email' => 'kavita.joshi@hrms.com',
                'phone' => '9876543217', 'gender' => 'Female', 'employee_id' => 'EMP008',
                'ctc' => 590000, 'employment_type' => 'full_time', 'employment_status' => 'active',
            ],
            [
                'first_name' => 'Suresh', 'last_name' => 'Iyer', 'email' => 'suresh.iyer@hrms.com',
                'phone' => '9876543218', 'gender' => 'Male', 'employee_id' => 'EMP009',
                'ctc' => 750000, 'employment_type' => 'full_time', 'employment_status' => 'active',
            ],
            [
                'first_name' => 'Meera', 'last_name' => 'Nair', 'email' => 'meera.nair@hrms.com',
                'phone' => '9876543219', 'gender' => 'Female', 'employee_id' => 'EMP010',
                'ctc' => 640000, 'employment_type' => 'full_time', 'employment_status' => 'active',
            ],
        ];

        $createdEmployees = [];
        $manager = null;

        foreach ($employeesData as $index => $empData) {
            $employee = Employee::firstOrCreate(
                ['employee_id' => $empData['employee_id']],
                array_merge($empData, [
                    'uuid' => (string) Str::uuid(),
                    'date_of_birth' => Carbon::now()->subYears(rand(25, 45))->format('Y-m-d'),
                    'joining_date' => Carbon::now()->subMonths(rand(1, 24))->format('Y-m-d'),
                    'department_id' => $departments->random()->id,
                    'designation_id' => $designations->random()->id,
                    'location_id' => $locations->random()->id,
                    'manager_id' => $manager ? $manager->id : null,
                    'address' => 'Sample Address ' . ($index + 1),
                    'city' => 'Mumbai',
                    'state' => 'Maharashtra',
                    'country' => 'India',
                    'postal_code' => '400001',
                    'emergency_contact_name' => 'Emergency Contact ' . ($index + 1),
                    'emergency_contact_phone' => '987654' . str_pad($index, 4, '0', STR_PAD_LEFT),
                    'emergency_contact_relation' => 'Spouse',
                    'bank_name' => 'HDFC Bank',
                    'bank_account_number' => '123456789' . str_pad($index, 2, '0', STR_PAD_LEFT),
                    'bank_ifsc' => 'HDFC0001234',
                    'bank_branch' => 'Mumbai Branch',
                    'pan_number' => 'ABCDE' . str_pad($index, 4, '0', STR_PAD_LEFT) . 'F',
                    'aadhar_number' => str_pad($index, 12, '0', STR_PAD_LEFT),
                    'is_active' => true,
                ])
            );

            // Create user account for employee
            $user = User::firstOrCreate(
                ['email' => $empData['email']],
                [
                    'name' => $empData['first_name'] . ' ' . $empData['last_name'],
                    'password' => Hash::make('password123'),
                    'employee_id' => $employee->id,
                    'is_active' => true,
                ]
            );
            $user->assignRole('Employee');

            // Set first employee as manager for others
            if ($index === 0) {
                $manager = $employee;
            } elseif ($index > 0) {
                $employee->update(['manager_id' => $manager->id]);
            }

            $createdEmployees[] = $employee;

            // Create attendance records for last 30 days
            for ($i = 0; $i < 30; $i++) {
                $date = Carbon::now()->subDays($i);
                // Skip weekends randomly (80% attendance)
                if (rand(1, 100) <= 80 && !$date->isWeekend()) {
                    $checkIn = $date->copy()->setTime(9, rand(0, 15), 0);
                    $checkOut = $date->copy()->setTime(18, rand(0, 30), 0);

                    Attendance::firstOrCreate(
                        [
                            'employee_id' => $employee->id,
                            'date' => $date->format('Y-m-d'),
                        ],
                        [
                            'uuid' => (string) Str::uuid(),
                            'check_in_time' => $checkIn,
                            'check_out_time' => $checkOut,
                            'check_in_method' => 'web',
                            'check_out_method' => 'web',
                            'status' => 'present',
                            'shift_id' => $shift->id,
                        ]
                    );
                }
            }

            // Create leave records (some pending, some approved)
            $leaveType = LeaveType::inRandomOrder()->first();
            $startDate = Carbon::now()->addDays(rand(5, 30));
            $endDate = $startDate->copy()->addDays(rand(1, 3));
            $totalDays = $startDate->diffInDays($endDate) + 1;

            Leave::create([
                'uuid' => (string) Str::uuid(),
                'employee_id' => $employee->id,
                'leave_type_id' => $leaveType->id,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'total_days' => $totalDays,
                'reason' => 'Personal work',
                'status' => $index % 3 === 0 ? 'pending' : ($index % 3 === 1 ? 'approved' : 'rejected'),
                'approved_by' => $index % 3 === 1 ? ($manager ? $manager->id : null) : null,
                'approved_at' => $index % 3 === 1 ? Carbon::now()->subDays(rand(1, 5)) : null,
                'rejection_reason' => $index % 3 === 2 ? 'Not enough leave balance' : null,
                'rejected_by' => $index % 3 === 2 ? ($manager ? $manager->id : null) : null,
                'rejected_at' => $index % 3 === 2 ? Carbon::now()->subDays(rand(1, 3)) : null,
            ]);

            // Create payroll records for last 3 months
            for ($month = 1; $month <= 3; $month++) {
                $payDate = Carbon::now()->subMonths($month);
                $payPeriodStart = $payDate->copy()->startOfMonth();
                $payPeriodEnd = $payDate->copy()->endOfMonth();
                $workingDays = $payPeriodStart->diffInWeekdays($payPeriodEnd) + 1;
                $presentDays = rand(18, $workingDays - 2);
                $leaveDays = rand(0, 3);
                $absentDays = $workingDays - $presentDays - $leaveDays;

                $basicSalary = ($empData['ctc'] / 12) * 0.5;
                $hra = ($empData['ctc'] / 12) * 0.2;
                $transport = ($empData['ctc'] / 12) * 0.05;
                $medical = ($empData['ctc'] / 12) * 0.05;
                $specialAllowance = ($empData['ctc'] / 12) * 0.2;

                $grossSalary = $basicSalary + $hra + $transport + $medical + $specialAllowance;
                $pf = min($basicSalary * 0.12, 1800);
                $esi = $grossSalary > 21000 ? 0 : $grossSalary * 0.0075;
                $pt = 200;
                $tds = ($grossSalary - $pf - $esi - $pt) > 250000 ? ($grossSalary - $pf - $esi - $pt - 250000) * 0.05 : 0;

                $totalDeductions = $pf + $esi + $pt + $tds;
                $netSalary = $grossSalary - $totalDeductions;

                Payroll::firstOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'month' => $payDate->month,
                        'year' => $payDate->year,
                    ],
                    [
                        'uuid' => (string) Str::uuid(),
                        'pay_period_start' => $payPeriodStart->format('Y-m-d'),
                        'pay_period_end' => $payPeriodEnd->format('Y-m-d'),
                        'working_days' => $workingDays,
                        'present_days' => $presentDays,
                        'leave_days' => $leaveDays,
                        'absent_days' => $absentDays,
                        'basic_salary' => $basicSalary,
                        'earnings' => json_encode([
                            'basic' => $basicSalary,
                            'hra' => $hra,
                            'transport' => $transport,
                            'medical' => $medical,
                            'special_allowance' => $specialAllowance,
                        ]),
                        'deductions' => json_encode([
                            'pf' => $pf,
                            'esi' => $esi,
                            'professional_tax' => $pt,
                            'tds' => $tds,
                        ]),
                        'statutory' => json_encode([
                            'pf_employee' => $pf,
                            'pf_employer' => $pf,
                            'esi_employee' => $esi,
                            'esi_employer' => $grossSalary > 21000 ? 0 : $grossSalary * 0.0325,
                        ]),
                        'gross_salary' => $grossSalary,
                        'total_deductions' => $totalDeductions,
                        'net_salary' => $netSalary,
                        'status' => 'paid',
                        'paid_date' => $payPeriodEnd->copy()->addDays(5)->format('Y-m-d'),
                        'approved_by' => $manager ? $manager->id : null,
                        'approved_at' => $payPeriodEnd->copy()->addDays(1),
                    ]
                );
            }
        }

        // Seed default ESS tasks for first employee (e.g. onboarding, training)
        $firstEmployee = Employee::where('employee_id', 'EMP001')->first();
        $adminUser = User::where('email', 'admin@hrms.com')->first();
        if ($firstEmployee && $adminUser) {
            $taskData = [
                [
                    'title' => 'Complete onboarding documents',
                    'description' => 'Submit all required documents for onboarding',
                    'employee_id' => $firstEmployee->id,
                    'due_date' => now()->addDays(7),
                    'priority' => 'high',
                    'status' => 'pending',
                    'action_route' => 'ess.onboarding-documents',
                    'action_label' => 'Submit documents',
                    'created_by' => $adminUser->id,
                ],
                [
                    'title' => 'Attend training session',
                    'description' => 'Complete mandatory training session',
                    'employee_id' => $firstEmployee->id,
                    'due_date' => now()->addDays(3),
                    'priority' => 'medium',
                    'status' => 'in_progress',
                    'action_route' => 'ess.training-session',
                    'action_label' => 'View details',
                    'created_by' => $adminUser->id,
                ],
            ];
            foreach ($taskData as $data) {
                EmployeeTask::firstOrCreate(
                    [
                        'employee_id' => $data['employee_id'],
                        'title' => $data['title'],
                    ],
                    $data
                );
            }
        }

        $this->command->info('Created 10 sample employees with attendance, leave, and payroll records!');
    }
}

