import React from 'react';

function paypal(){

    window.paypal.Buttons().render('#paypal-button');
    return (
        <div id="paypal-button"></div>
    );
}
export default paypal;