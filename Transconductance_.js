//TODO:excution('匹配对象', '匹配数组')
excution('乔治敦', InsGlobalPort);
function excution(value, arr) {
    var reflection = main(value, arr);
    console.log(value, reflection);
    return reflection;
}

//测试接口
function excution_test(arr1, arr2) {
    var count = 0;
    var startedTime = new Date().getTime();
    for(var i=0; i<arr1.length; i++) {
        var string = main(arr1[i][1], arr2);
        console.log(count,arr1[i][1],string);
        count++;
    }
    var finishedTime = new Date().getTime();
    var cost = finishedTime-startedTime;
    console.log(cost);
}

function main(search, arr)
{
    var selfDefinateArr = [["台澎金马关税区", "中国台湾"]];
    var reflection;
    $.each(arr, function(index, value) {
        if(slimString(value[1]) === slimString(search) || slimString(search) === transform(slimString(value[1]), selfDefinateArr)) {
            reflection = value;
        }
    });

    if(reflection) {
        return reflection;
    } else {
        reflection = fixSame_oneToMany(search, arr);
        return reflection;
    }
}

function indexOfVal(arr, val) {
    var loop = arr.length;
    for(var i=0; i<loop; i++) {
        if(arr[i] === val) {
            return i;
        }
    }
}

function compare(arr, value) {
    var i =0;
    for(i; i<arr.length; i++) {
        if(arr[i] === value) {
            return true;
        }
    }
    return false;
}

function slimString(value) {
    var temp_value = value;
    temp_value = temp_value.replace(/\s/g, '');
    temp_value = temp_value.replace(/（/g, '(');
    temp_value = temp_value.replace(/）/g, ')');
    return temp_value;
}

function transform(value, arr) {
    for(var i=0; i<arr.length; i++) {
        if(value === slimString(arr[i][0])) {
            return slimString(arr[i][1]);
        }
    }
}

function sortArrByLen(a, b)
{
    return -(a[1].length - b[1].length);

}

function fixSame_oneToMany(string, arr) {
    var plusesChar = ['(', '市', '县', '州', '国', '省', '区'];
    var like= [];
    var defaultPercent = 1.2;
    var T_start = new Date().getTime();
    var countTimes = 2;
    for (var count=1; count<=countTimes; count++) {
        var n = 0;
        for(var i=0; i<arr.length; i++) {
            var subChar = [];

            if(string.length !== arr[i][1].length) {
                var shortOne = string.length < arr[i][1].length ? string : arr[i][1];
                var longOne = string.length > arr[i][1].length ? string : arr[i][1];
            } else {
                shortOne = string;
                longOne = arr[i][1];
            }

            for(var k=0; k<longOne.length; k++) {
                subChar[k] = longOne.substr(k, 1);
            }

            var l = 0;
            var queueSameChar = [];
            var indexQueueSameChar = 0;
            for(var j=0; j<shortOne.length; j++) {
                if(compare(subChar, shortOne.substr(j,1))) {
                    queueSameChar[indexQueueSameChar] = indexOfVal(subChar, shortOne.substr(j,1));
                    if(indexQueueSameChar === 0) {
                        l++;
                    } else {
                        if(queueSameChar[indexQueueSameChar] > queueSameChar[indexQueueSameChar-1]) {
                            l++;
                        }
                    }
                    indexQueueSameChar++;
                }
                if(shortOne.substr(0,2) === longOne.substr(0,2)) {
                    l = l + 0.1;
                }
                // console.log(slimString(longOne.substr(shortOne.length-1, 1)));
                if(compare(plusesChar, slimString(longOne).substr(shortOne.length, 1))) {
                    l = l + 0.1;
                }
            }

            if(l > 0) {
                var percent = shortOne.length/l;
                if(count === 1) {
                    var percent_old = percent;
                    var result = arr[i];
                } else {
                    if(percent <= percent_old) {
                        percent_old = percent;
                        result = arr[i];
                        if(percent_old<=defaultPercent) {
                            like[n] = result;
                            n++;
                        }
                    } else {
                        var T = new Date().getTime()-T_start/1125;
                        if(Math.random()>Math.exp(-(percent- percent_old)/T)) {
                            percent_old = percent;
                            result = arr[i];
                            if(percent_old<=defaultPercent) {
                                like[n] = result;
                                n++;
                            }
                        }
                    }
                }
            }

        }
    }
    var m = 0;
    var finalLike = [];
    for(var t=like.length-1; t>=0; t--) {
        finalLike[m] = like[t];
        m++;
    }
    return finalLike;
}

