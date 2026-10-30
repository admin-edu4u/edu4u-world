@extends('memberLayout')
@section('style')
@endsection
@section('content')
<div class="pt-80 pb-20">
  <div class="blog-header">
    <div class="mw-1200 mx-auto">
      <div class="d-flex align-items-center">
        <svg class="me-10" xmlns="http://www.w3.org/2000/svg" width="10" height="18" viewBox="0 0 10 18" fill="none">
          <path d="M9 1L1 9L9 17" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span class="blog-header-title">Event Highlights</span>
      </div>
    </div>
  </div>
  <div class="mw-1200 mx-auto mt-10">
    <span class="blog-carousel-header d-block">How to do More with Less in 2023</span>
    <span class="blog-recommend-date d-block pt-3">01 May 2023</span>
    <div class="col-12 row mt-5">
      <div class="col-7 order-1 mt-5">
        <img class="col-12 br-8 event-details-img" src="/images/assets/blog-img.png">
      </div>
      <div class="col-5 order-2 mt-5">
        <img src="/images/assets/blog-img.png" class="col-12 br-8 event-details-img">
      </div>
      <div class="col-7 order-2 mt-5">
        <img class="col-12 br-8 event-details-img" src="/images/assets/blog-img.png">
      </div>
      <div class="col-5 order-1 mt-5">
        <img src="/images/assets/blog-img.png" class="col-12 br-8 event-details-img">
      </div>
    </div>
    <div class="mt-10">
      <span class="blog-details-desc">
        BNB Chain has successfully completed its 25th quarterly BNB burn. The burn includes the Auto-Burn as well as the Pioneer Burn Program.<br>
        Here are the facts and figures from the latest burn:
        <ul>
          <li>Auto-Burn (Total BNB burned): 2,139,182.98 BNB</li>
          <li>Approximate value in USD: ~$501,617,017</li>
          <li>Transaction ID (TXID) for BNB burn: View transaction</li>
          <li>BNB burned from the Pioneer Burn Program: 314.69 BNB</li>
          <li>Real Burn (Auto-Burn - Pioneer Burn): 2,138,868.29</li>
        </ul>

        <br><br><span class="blog-carousel-header">BNB What You Need to Know About the BNB</span><br>
        Burn BNB is the cryptocurrency that fuels the BNB Chain ecosystem. It acts as the native coin of the BNB Beacon Chain, the BNB Smart Chain, and the opBNB L2s and BNB Greenfield blockchain. It also serves as a governance token and allows holders to transact with projects built on those chains.

        When BNB was launched in 2017, a commitment was made to remove 100 million BNB (half of its total supply) from circulation through a burning process. This ultimately will keep only 100 million BNB in circulation. This commitment is automatically honored each quarter and calculated according to the Auto-Burn formula.

        The BNB Auto-Burn provides an independently auditable, objective process. The figures are reported quarterly, and the mechanism is independent of the Binance centralized exchange. On top of that, BNB Chain continues to burn a portion of BNB Chain’s gas fees in real-time using the Real-Time-Burn mechanism. To learn more about the BNB Burn, refer to this Binance Academy article.

        <br><br><span class="blog-carousel-header">Pioneer Burn Program</span><br>
        The Pioneer Burn Program also permanently removes an amount of BNB from circulation equal to provable lost funds by eligible BNB Chain users. This number is then counted towards the total quarterly burn figure. More details can be found here.

        When it comes to the Pioneer Burn, BNB Auto-Burn, and Real-Time-Burn (BEP95) mechanisms, everything has stayed the same as in the 24th BNB burn. As always, these are transparent and independently verifiable.

        Since the introduction of BEP95, roughly 146,650 BNB has been burnt under this mechanism.
      </span>
    </div>
  </div>
</div>
@endsection

@section('script')
@endsection
