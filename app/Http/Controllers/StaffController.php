<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\salary;
use App\Models\staff_salary;
use App\Models\pay_salary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class StaffController extends Controller
{
    public function staff(){

        $staff= Staff::all();
        return view('staff.index',compact('staff'));
    }








    public function addstaff(Request $request)
{
    // Validate the incoming request data

  $staff=new Staff();
  $staff->full_name=$request->staff_name;
  $staff->nic=$request->nic;
  $staff->mobile=$request->mobile;
  $staff->save();
        // Return success response
        return redirect()->back()->with('success', 'Successfully added');

}



public function updatestaff(Request $request, $id)
{
    // Validate the incoming request data

    // Find the staff by the provided ID
    $staff = Staff::find($id);  // Corrected this line

    // If staff doesn't exist, you can return an error message
    if (!$staff) {
        return redirect()->back()->with('error', 'Staff not found');
    }

    // Update the staff record
    $staff->update([
        'full_name' => $request->input('staff_name'),
        'nic' => $request->input('nic'),
        'mobile' => $request->input('mobile'),
    ]);

    // Return a success message
    return redirect()->back()->with('success', 'Successfully updated the staff.');
}




//deletestaff

public function deletestaff($id){
    $staff=Staff::find($id);
    $staff->delete();
    return redirect()->back()->with('success','staff deleted');
}








    public function showsalary(){

        $salary = DB::table('pay_salary')

        ->join('staffs', 'staffs.id', '=', 'pay_salary.staff_id')
        ->select('pay_salary.*','staffs.full_name'


        )

        ->get();


      return view('salary.index',compact('salary'));
    }



    public function getSalaryDetails($sell_id)
{
    $salaries = DB::table('salary')
        ->join('sells', 'salary.sells_id', '=', 'sells.id')
        ->leftJoin('staffs', 'salary.staff_id', '=', 'staffs.id')
        ->select(
            'staffs.full_name',
            'salary.id',
            'salary.payment AS salary_amount',
            'salary.paid AS salary_paid',
            'salary.due AS salary_due',
            'salary.salary_status'
        )
        ->where('salary.sells_id', $sell_id)
        ->orderBy('salary.id', 'desc')
        ->get();

    return response()->json(['salaries' => $salaries]);
}

public function paystaffSalary(Request $request)
{
    $request->validate([
        'staff_id' => 'required|exists:pay_salary,staff_id',
        'payment_amount' => 'required|numeric|min:1',
    ]);

    // Fetch the salary record for the given staff_id
    $salary = pay_salary::where('staff_id', $request->staff_id)->firstOrFail();

    // Calculate the remaining due
    $totalDue = $salary->payment - $salary->paid;

    // Ensure the payment amount does not exceed the total due
    if ($request->payment_amount > $totalDue) {
        return redirect()->back()->withErrors(['error' => 'Payment amount cannot exceed the total due.']);
    }

    // Update payment
    $salary->paid += $request->payment_amount;
    $salary->payment_date = now();
    $salary->save();

    return redirect()->back()->with('success', 'Salary payment recorded successfully.');
}


}
