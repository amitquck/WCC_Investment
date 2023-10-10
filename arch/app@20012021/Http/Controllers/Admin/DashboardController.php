<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\User;
use App\Model\CustomerInvestment;
use App\Model\CustomerTransactions;
use App\Model\CustomerReward;
use App\Model\AssociateReward;
use App\Model\AssociateTransaction;
use App\Model\AssociateCommissionPercentage;
use Auth;
use Artisan;

// use App\Model\CustomerService;
class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $total_customer = User::whereLoginType('customer')->count();
        $total_deposit = CustomerTransactions::where('cr_dr','cr')->where('transaction_type','deposit')->sum('amount');
        $total_interest = CustomerReward::sum('amount');
        $total_associate_commission = AssociateReward::sum('amount');
        $total_associate_customer = AssociateCommissionPercentage::whereAssociateId(Auth::user()->id)->count();
        // dd($total_associate_customer);
        $latest_invest = CustomerTransactions::where('cr_dr','cr')->where('transaction_type','deposit')->orderBy('deposit_date', 'DESC')->limit(5)->get();
        $current_invest = CustomerTransactions::where('cr_dr','cr')->where('transaction_type','deposit')->whereBetween('deposit_date',[date('Y-m-d 00:00:01'),date('Y-m-t 23:59:59')])->orderBy('deposit_date', 'DESC')->limit(5)->get();

        $top_invest = CustomerInvestment::groupBy('customer_id')->orderByRaw('SUM(amount) DESC')->limit(5)->get();
        // dd($top_invest);
        $data['deposit'] = [];
        $data['interest'] = [];
        $data['withdraw'] = [];
        $data['commission'] = [];
        $customerdata['deposit'] = [];
        $customerdata['interest'] = [];
        $customerdata['withdraw'] = [];
        $associatedata['commission'] = [];
        $associatedata['withdraw'] = [];
        
        for($i = 1; $i <= 12; $i++){
            $start_date = date("Y").'-'.sprintf("%02d",$i)."-01 00:00:00";
            // dd($start_date);
            $end_date = date("Y-m-t 23:59:59",strtotime($start_date));
            // dd($end_date);
            $data['deposit'][$i] = CustomerInvestment::whereBetween('deposit_date', [$start_date, $end_date])->sum('amount');

            $data['interest'][$i] = CustomerTransactions::whereBetween('deposit_date', [$start_date, $end_date])->where('cr_dr','cr')->where('transaction_type','interest')->sum('amount');
             $data['withdraw'][$i] = CustomerTransactions::whereBetween('deposit_date', [$start_date, $end_date])->where('cr_dr','dr')->where('transaction_type','withdraw')->sum('amount');
             $data['commission'][$i] = AssociateTransaction::whereBetween('deposit_date', [$start_date, $end_date])->where('cr_dr','cr')->where('transaction_type','commission')->sum('amount');

            $customerdata['deposit'][$i] = CustomerInvestment::whereCustomerId(Auth::user()->id)->whereBetween('deposit_date', [$start_date, $end_date])->sum('amount');

            $customerdata['interest'][$i] = CustomerTransactions::whereCustomerId(Auth::user()->id)->whereBetween('deposit_date', [$start_date, $end_date])->where('cr_dr','cr')->where('transaction_type','interest')->sum('amount');
             $customerdata['withdraw'][$i] = CustomerTransactions::whereCustomerId(Auth::user()->id)->whereBetween('deposit_date', [$start_date, $end_date])->where('cr_dr','dr')->where('transaction_type','withdraw')->sum('amount');


            $associatedata['withdraw'][$i] = AssociateTransaction::whereAssociateId(Auth::user()->id)->where('cr_dr','dr')->whereBetween('deposit_date', [$start_date, $end_date])->where('transaction_type','withdraw')->sum('amount');
            $associatedata['commission'][$i] = AssociateTransaction::whereAssociateId(Auth::user()->id)->whereBetween('deposit_date', [$start_date, $end_date])->where('cr_dr','cr')->where('transaction_type','commission')->sum('amount');
            // dump($data);

            // $start_date = (date("Y")-1).'-'.sprintf("%02d",$i)."-01 00:00:00";
            // $end_date = date("Y-m-t 23:59:59",strtotime($start_date));

            // $data['monthly_last_year_orders'][$i] = CustomerDeposit::whereBetween('created_at', [$start_date, $end_date])->sum('amount');
        }
        // dd($data);
        // $var = [];
        // foreach($data['deposit'] as $key => $thi){
        //     dd($thi);
        //     $var = $thi;
        // }
        // $c = implode(',',$var);
        // dd($c);
        return view('admin.dashboard',compact('total_customer','total_deposit','total_interest','total_associate_commission','latest_invest','current_invest','top_invest','data','customerdata','associatedata','total_associate_customer'));
    }

    public function backup(){
        // dd(Auth()->user()->login_type);
        //ENTER THE RELEVANT INFO BELOW
        $mysqlHostName      = env('DB_HOST');
        $mysqlUserName      = env('DB_USERNAME');
        $mysqlPassword      = env('DB_PASSWORD');
        $DbName             = env('DB_DATABASE');
        $backup_name        = "mybackup.sql";
        $except_tables      = array("cities","states","countries"); //here your tables...

        $connect = new \PDO("mysql:host=$mysqlHostName;dbname=$DbName;charset=utf8", "$mysqlUserName", "$mysqlPassword",array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        $get_all_table_query = "SHOW TABLES";
        $statement = $connect->prepare($get_all_table_query);
        $statement->execute();
        $tables = $statement->fetchAll();

        $output = '';
        foreach($tables as $table_names){
            $table = $table_names[0];
            if(!in_array($table,$except_tables)){
                $show_table_query = "SHOW CREATE TABLE " . $table . "";
                $statement = $connect->prepare($show_table_query);
                $statement->execute();
                $show_table_result = $statement->fetchAll();

                foreach($show_table_result as $show_table_row){
                    $output .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
                }
                $select_query = "SELECT * FROM " . $table . "";
                $statement = $connect->prepare($select_query);
                $statement->execute();
                $total_row = $statement->rowCount();

                for($count=0; $count<$total_row; $count++){
                    $single_result = $statement->fetch(\PDO::FETCH_ASSOC);
                    $table_column_array = array_keys($single_result);
                    $table_value_array = array_values($single_result);
                    $output .= "\nINSERT INTO $table (";
                    $output .= "" . implode(", ", $table_column_array) . ") VALUES (";
                    $output .= "'" . implode("','", $table_value_array) . "');\n";
                }
            }
        }
        $file_name = 'database_backup_on_' . date('Y-m-d-H-i') . '.sql';
        $file_handle = fopen($file_name, 'w+');
        fwrite($file_handle, $output);
        fclose($file_handle);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file_name));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_name));
        ob_clean();
        flush();
        readfile($file_name);
        unlink($file_name);
        dd('done');
    }
}
