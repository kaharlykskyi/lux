<?php
/**
 * Created by PhpStorm.
 * User: Seliv
 * Date: 13.12.2018
 * Time: 11:52
 */

namespace App\TecDoc;


class ImportPriceList
{

    public function getMail(){
        $price_list_configs = config('price_list_settings');
        foreach ($price_list_configs as $config){
            $connect_to = '{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX';
            $attachment_id =array();
            $user = $config['email'];
            $password = $config['password'];

            try{
                $imap = imap_open($connect_to, $user, $password);
                $message_count = imap_num_msg($imap);
                if ($message_count > 0){
                    $attachments = array();
                    $email = array();
                    for ($m = 1; $m <= $message_count; ++$m){
                        $header = imap_header($imap, $m);
                        $email[$m]['from'] = $header->from[0]->mailbox.'@'.$header->from[0]->host;
                        $email[$m]['fromaddress'] = $header->from[0]->personal;
                        $email[$m]['to'] = $header->to[0]->mailbox;
                        $email[$m]['subject'] = $header->subject;
                        $email[$m]['message_id'] = $header->message_id;
                        $email[$m]['date'] = $header->udate;
                        $from = $email[$m]['fromaddress'];
                        $from_email = $email[$m]['from'];

                        $to = $email[$m]['to'];
                        $subject = $email[$m]['subject'];

                        $structure = imap_fetchstructure($imap, $m);

                        if(isset($structure->parts) && count($structure->parts)) {
                            for($i = 0; $i < count($structure->parts); $i++) {
                                $attachments[$i] = array(
                                    'is_attachment' => false,
                                    'filename' => '',
                                    'name' => '',
                                    'attachment' => ''
                                );

                                
                            }
                        }
                    }
                }

                return $message_count = imap_num_msg($imap);
            } catch (\Exception $exception){
                if (config('app.debug')){
                    dump("Can't connect to '$connect_to': " . imap_last_error());
                }
            }

        }
    }

    protected function export(){

    }
}