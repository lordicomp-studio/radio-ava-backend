export function putUnitForSize (size) {
    if (size === '-'){
        return '-';
    }

    let numLength = size.toString().length;
    if(numLength > 9){
        return (size/1000000000).toFixed(2) + "GB";
    }else if(numLength > 6){
        return (size/1000000).toFixed(2) + "MB";
    }else if (numLength > 3){
        return (size/1000).toFixed(2) + "KB";
    }else{
        return size + "B";
    }
}
