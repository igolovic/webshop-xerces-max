function addToCart(id, quantity)
{   
    alterOrAddRowId = id;
    
    if(quantity < 1)
    {        
        return;
    }
    
    $.ajax(
           {type: 'POST',
            url: '/lcm-cart/add-to-cart',
            data: 'id=' + id + '&quantity=' + quantity,
            dataType: 'json',
            success: function(cartTotals)
            {
                // refresh row if altered (not added)
                var tdSingleCartItemPriceTotal = document.getElementById('tdSingleCartItemPriceTotal' + alterOrAddRowId);
                
                if(tdSingleCartItemPriceTotal != null)
                {
                    tdSingleCartItemPriceTotal.innerHTML = document.getElementById('tdSingleCartItemPrice' + alterOrAddRowId).innerHTML * document.getElementById('inpQuantity' + alterOrAddRowId).value; 
                }
                
                // refresh totals
                var el1 = document.getElementById('spanCartItemCount');
                var el2 = document.getElementById('spanCartValueTotal');
                
                if(el1 != null && el2 != null)
                {
                    el1.innerHTML = cartTotals.cartItemCount;
                    el2.innerHTML = cartTotals.cartValueTotal;
                }
                
                var el3 = document.getElementById('spanCartItemCount2');
                var el4 = document.getElementById('spanCartValueTotal2');
                
                if(el3 != null && el4 != null)
                {
                    el3.innerHTML = cartTotals.cartItemCount;
                    el4.innerHTML = cartTotals.cartValueTotal;
                }
            }
    });

}

function removeFromCart(id)
{  
    removeRowId = id;   
    $.ajax({
                type: 'POST',
                url: '/lcm-cart/remove-from-cart',
                data: 'id=' + id,
                dataType: 'json',
                success: function(cartTotals)
                {
                    // remove deleted row
                    var rows = document.getElementById('cartItems').rows;
                    
                    if(rows != null)
                    {                        
                        for(var i=0; i<rows.length; i++)
                        {
                            if(rows[i].id == ('tr' + removeRowId))
                            {
                                document.getElementById('cartItems').deleteRow(i);
                                break;
                            }

                            // if deleted row was last (except for the header and footer row)
                            // remove 'Isprazni košaricu' and 'Na blagajnu' buttons
                            if((rows.length - 1 ) == 2)
                            {
                                var el1 = document.getElementById('inpOrder');
                                var el2 = document.getElementById('inpEmpty');
                                if(el1 != null && el2 != null)
                                {
                                    var parent = document.getElementById('sendOrderContainer');
                                    parent.removeChild(el1);
                                    parent.removeChild(el2);
                                }
                            }
                        }
                    }
                    refreshTotals(cartTotals);
                }
    });
}

function emptyCart()
{    
    $.ajax({
            type: 'POST',
            url: '/lcm-cart/empty-cart',
            dataType: 'json',
            success: function(cartTotals)
            {
                    // remove all rows
                    var t = document.getElementById('cartItems');
                    
                    if(t != null)
                    {
                        for(var i=t.rows.length-1; i>1; i--)
                        {
                            t.deleteRow(i-1);
                        }
                    }
                    
                    // remove 'Isprazni košaricu' and 'Na blagajnu' buttons
                    var el1 = document.getElementById('inpOrder');
                    var el2 = document.getElementById('inpEmpty');
                                        
                    if(el1 != null && el2 != null)
                    {
                        var parent = document.getElementById('sendOrderContainer');
                        parent.removeChild(el1);
                        parent.removeChild(el2);
                    }
                    
                    refreshTotals(cartTotals);
            }
    });

}

function refreshTotals(cartTotals)
{                    
    var el1 = document.getElementById('spanCartItemCount');
    var el2 = document.getElementById('spanCartValueTotal');

    if(el1 != null && el2 != null)
    {
        el1.innerHTML = cartTotals.cartItemCount;
        el2.innerHTML = cartTotals.cartValueTotal;
    }

    var el3 = document.getElementById('spanCartItemCount2');
    var el4 = document.getElementById('spanCartValueTotal2');

    if(el3 != null && el4 != null)
    {
        el3.innerHTML = cartTotals.cartItemCount;
        el4.innerHTML = cartTotals.cartValueTotal;
    }
}

function sendForm(url, requiredData, nonRequiredData, isOrder)
{    
    document.getElementById('divOk').style.display = 'none';
    document.getElementById('divErr').style.display = 'none';
 
    if(checkForm(requiredData))
    {    
        var data = requiredData.concat(nonRequiredData);
        var dataString = '';
        
        for(var i=0; i<data.length; i++)
        {
              dataString += '&' + data[i][0].toLowerCase()+ '=' + data[i][1];          
        }
        
        dataString = dataString.replace(/^&/, '');
        
        $.ajax({type: 'POST',
                url: url,
                dataType: 'json',
                data: dataString,
                success: function(response)
                {
                    if(isOrder)
                    {
                        if(response.status == '1')
                        {
                           document.getElementById('divOk').style.display = 'block';
                           document.getElementById('spanClientId').innerHTML = response.clientId;
                           document.getElementById('spanOrderId').innerHTML = response.orderId;
                        }
                        else
                        {
                           document.getElementById('divErr').style.display = 'block';     
                        }
                    }
                    else
                    {
                        if(response.status == '1')
                        {
                           document.getElementById('divOk').style.display = 'block';
                        }
                        else
                        {
                           document.getElementById('divErr').style.display = 'block';
                        }      
                    }
                }
        });
    }
}

function checkForm(requiredData)
{
    for(var i=0; i<requiredData.length; i++)
    {
        if(requiredData[i][1].length == 0)
        {
            document.getElementById('a' + requiredData[i][0] + 'Err').onclick();
            return false;
        }
    }
    return true;
}