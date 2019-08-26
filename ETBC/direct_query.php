
/*금액 - 인사람 찾기*/

select mb_no,mb_id,mb_save_point,mb_balance,mb_shift_amt,mb_deposit_point,mb_deposit_acc,mb_deposit_calc,A.total  from (select *, mb_save_point + mb_balance + mb_shift_amt + mb_deposit_calc as total from g5_member) A where A.total < 0