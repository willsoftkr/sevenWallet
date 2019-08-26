/**
 * Ethereum ERC20 Reborn Block Token Sample
 *
 * @author Chaineers co. Harry Oh
 *
 * 윌소트프의 요구사항에 따른 기능 구현 코드들
 * 요구사항
 * - 아래 상황의 샘플코드로 작성
 * - Ethereum Ethereum Account Create
 * - Ethereum ERC20 Token Balance
 * - Ethereum ERC20 Token Transfer
 * - Ethereum ERC20 Token History
 *
 * 
 * Reborn Block Token Class를 작성하여 사용하기를 추천합니다. 기능의 나열을 위해서 함수 기반으로 
 * 작성되었지만 Class로 작성시 코드를 좀 더 명확하게 할 수가 있고 불필요한 전역변수들이 사라질 수
 * 있습니다.
 * 소스코드의 모든 권한은 윌소프트에게 있음을 인정합니다.
 * 작성자: (주)체이니어스 오회근
 *
 * 최좀수정일자: 2018.07.29
 */

/**
* @constructor
**/
$(function() {
  /**
   * web3 인스턴스를 담을 전역변수
   * @typeof
   */
  let web3;

  /**
   * Ethereum Blockchain에 배포된 ERC20 Smart Contract 인스턴스를 담을 전역변수
   * @typeof
   */
  let erc20_contract;

  /**
   * Ethereum Endpoint를 가져온다.
   * @func
   */
  const get_endpoint = function() { return $("#ethereum_endpoint").val(); }

  /**
   * ERC20 Token Smart Contract 주소를 가져온다.
   * @func
   */
  const get_contract_address = function() { return $("#contract_address").val(); }

  /**
   * 미리 저장된 계정의 정보를 가져온다. 모두 Test계정이기 때문에 유출 염려는 없다.
   * 단, Mainnet에서는 여기 저장된 계정을 사용하면 안된다.
   * 실 서버에서 구현할 때에는 계정은 이미 암호화되어 DB에 저장되어야하고 필요할때 복호화 과정을 통해서
   * 사용한다.
   * @func
   */
  let get_accounts = function() {
    return {
      "0x84997022e25a6B17F66439de75b5db966Bd6899D": "0x7ec305044f0305314487e2a871570d2a33b424c81ef7cae29e49f91e88d509e2",
      "0x22442CF557b95A268786Cc53379fE2944C368107": "0xca59dc0decdfcb344d43f70249be9125b0ddf454833a7c77ed9689fc07435296",
      "0xbb189CFA7eCf82461626236559CeA29e234B9e3c": "0x485aab257ce82806fbe5631d200a8af8b4ea37f67504421a3f5b117f708f71e7"
    }
  }

  /**
   * 페이지가 로딩할 때에 처음으로 실행하는 함수이다.
   * Class일 경우 생성자에서 하는 일과 비슷하다.
   * web3 인스턴스를 생성하고 erc20_contract 인스턴스를 생성한다.
   * 그리고 미리 정의된 계정을 HTML상에서 보여준다.
   * @func
   */
  const initial_web3 = function() {
    let ethereum_endpoint = get_endpoint();
    let contract_address = get_contract_address();
    if (typeof web3 !== 'undefined') {
      web3 = new Web3(web3.currentProvider);
    } else {
      web3 = new Web3(new Web3.providers.HttpProvider(ethereum_endpoint));
      erc20_contract = new web3.eth.Contract(erc20_abi, contract_address);
    }
    idx = 0;
    for (addr in get_accounts()) {
      $('#acc_addr span').eq(idx).text(addr);
      idx++;
    }
  }

  /**
   * publickey에 해당하는 privateKey를 가져온다.
   * 실 서비스에서는 위험한 코드이므로 별도의 프로세서를 이용하여 처리하는 편이 좋다.
   * @func
   * @param publickey privateKey를 가져올 publickey
   */
  const get_privatekey = function (publickey) {
    let accounts = get_accounts();
    if (accounts.hasOwnProperty(publickey)) {
      return accounts[publickey];
    }

    return false;
  };

  /**
   * Ethereum Account를 생성한다.
   * Account를 생성한다고 해서 바로 Ethereum Node에 등록되는 것이 아니다.
   * Account Key를 이용하여 Transaction에 일으킬때 사용이 된다. 따라서 Account를 생성할때는
   * Ethereum Node를 사용할 필요가 없으므로 매우 빠르게 생성될 수가 있다.
   * @func
   */
  const create_account = function() {
    account = web3.eth.accounts.create();
    return {
      address: account.address,
      privateKey: account.privateKey
    };
  }

  /**
   * 계정의 토큰 갯수를 반환한다.
   * @func
   * @param address 토큰의 갯수를 알기 원하는 address
   * @param callback 실행 완료후 호출될 함수
   */
  const get_balanceOf = function(address, callback) {
   web3.eth.getBalance(address).then(res => {    return callback(res);
    });
  };

  /**
   * 토큰을 송금한다.
   * @func
   * @param from_address 토큰을 보낼 주소
   * @param to_address 토큰을 받을 주소
   * @param gas_limit 최대 gas의 수
   * @param amount 보낼 토큰의 수
   * @param callback 실행 완료후 호출될 함수
   */
  const send_token = function(from_address, to_address, gas_limit, amount, callback) {
    let data = erc20_contract.methods.transfer(to_address, amount).encodeABI();
    const nonce = web3.eth.getTransactionCount(from_address);

    let txdata = {
      "from": '0x61276DaA9ef80a4Cef2AB9d7ef9e10DbA71B9597',
      "to": get_contract_address(),
      "gas": web3.utils.toHex(210000),
      "value": "0x00",
//      "data": data
    };

    /**
     * Transaction이 일어난 후에 상태의 변화에 따라서 별도의 작업을 처리할 수가 있다.
     * 여기서는 단순히 log만 출력하고 있다.
     */
    web3.eth.accounts.signTransaction(txdata,'0xae4f4a50c70e83bb5b1560faebb5c2eed3bddef8a4e32aec0c9f91d8aa6614b5')
    .then(signed => {
      web3.eth.sendSignedTransaction(signed.rawTransaction)
      .once('transactionHash', function(hash) { console.log(hash) })
      .once('receipt', function(receipt) { console.log(receipt) })
      .on('confirmation', function(confNumber, receipt) { console.log(confNumber, receipt) })
      .on('error', error => { return callback(error, null); })
      .then(function(receipt) {
        console.log('Done');
        return callback(null, receipt);
      });
    })
  };

  /**
   * ERC20 Token 거래 기록을 보여준다.
   * @func
   * @param address 거래기롤을 볼 주소
   * @param transaction_type 송금(deposit)과 출금(withdraw)을 구분한다.
   * @param callback 실행 후에 호출될 함수
   */
  const get_history = function(address, transaction_type, callback) {
    let filter_name = (transaction_type === 'deposit') ? 'from':'to';
    erc20_contract.getPastEvents('Transfer', {
      fromBlock: 0,
      toBlock: 'latest',
      filter: {
        [filter_name]: address
      }
    }, (error, events) => {
      if (error) {
        return callback(error, null);
      }
    }).then(event => {
      return callback(null, event);
    });
  };

  /**
   * Create Account Button
   */
  $('#create_account button').click(function() {
    account = create_account();
    $(this).siblings('#result').text(
      JSON.stringify(account, null, 2)
    );
  });

  /**
   * Get Balance Button
   */
  $('#get_balance button').click(function() {
    let address = $(this).siblings('input[type="text"]').val();
    if (!address) {
      alert('adderss is empty!');
      return false;
    }
    $this = $(this);

    get_balanceOf(address, balance => {
      $this.parent().siblings('#result').text(balance);
    });
  });

  /**
   * Send Token Button
   */
  $('#send_token button').click(function() {
    from = $('#from_address').val();
    to = $('#to_address').val();
    gas_limit = $('#gas_limit').val();
    amount = $('#amount').val();

    if (!from || !to || !gas_limit) {
      alert('there are empty values!');
      return false;
    }

    $this = $(this);
    $this.siblings('#result').text('processing...');
    send_token(from, to, gas_limit, amount, (error, res) => {
      if (error) {
        $this.siblings('#result').text(JSON.stringify(error, null, 2));
      } else {
        /**
         * Token을 송금한 이후에 호출되지만 발생된 Transaction의 Confirm은 계속 증가한다.
         */
        $this.siblings('#result').text(JSON.stringify(res, null, 2));
      }
    });
  });

  /**
   * Copy Button
   */
  $('#acc_addr button').click(function() {
    let addr = $(this).siblings('span').text();
    let $tempbox = $('<input>');

    $('body').append($tempbox);
    $tempbox.val(addr).select();
    successed = document.execCommand('copy');
    $tempbox.remove();
    if (successed) {
      alert(addr);
    }
  });

  /**
   * History Button
   *
   * 송금과 출금에 따라서 별도로 가져온다.
   * 가져온 기록을 파싱해서 필요한 부분만 보여주도록 했다.
   */
  $('#get_history button').click(function() {
    let address = $(this).siblings('input[type="text"]').val();
    if (!address) {
      alert('adderss is empty!');
      return false;
    }

    /**
     * Transfer를 통해서 송금을 할 경우에 Transfer Event에 기록을 한다.
     * 저장된 기록에는 Timestamp 정보를 알 수가 없지만 blockNumber를 알 수가 있다.
     * 얻은 blockNumber를 통해서 해당 Block의 Timestamp를 가져와서 시간을 처리한다.
     *
     * @param item timestamp를 가져올 transfer event
     */
    const _find_history_time = async function(item) {
      block = await web3.eth.getBlock(item.blockNumber);
      item['timestamp'] = block.timestamp
    };

    $this = $(this);
    get_history(address, $(this).attr('id'), async (error, history) => {
      let resultbox = $this.parent().siblings('#result');
      if (error) {
        resultbox.text(JSON.stringify(error, null, 2));
      } else {
        let info = ['Total: ' + history.length];
        const promises = history.map(_find_history_time);
        await Promise.all(promises);

        history.forEach((item, idx) => {
          info.push('[' + (idx+1) + ']');
          info.push('DateTime:' + new Date(item.timestamp*1000).toLocaleString());
          info.push('from:' + item.returnValues.from);
          info.push('to:' + item.returnValues.to);
          info.push('value:' + item.returnValues.value);
          info.push('');
        });
        $this.parent().siblings('#result').text(info.join('\n'));
      }
    });
  });

  initial_web3();
});
