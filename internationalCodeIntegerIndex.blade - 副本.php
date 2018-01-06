<html>
<head>
    <title>test</title>
    <meta charset="utf-8">
</head>
<body>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/basedata/CustomBaseData.js') }}"></script>
<script src="{{ asset('js/basedata/InspectionBaseData.js') }}"></script>
<script>
    //TODO:不要删除注释语句
   /* var test = [3,4];test2 = [2,3,4,5];
    loop = test2.length;
    for(i=0;i<loop; i++) {
        if(compare(test, test2[i])) {
            removeVal(test2, test2[i]);
            i = i-1;
            loop = loop-1;
        }
    }
    console.log(test2);*/

    main_country();
    function main_country()
    {
        var reflectionList = [];
        var same = [];
        var diff_CustomCountry = [];
        var diff_InsCountryZone = [];
        var foo = [];//middleware
        var i = 0;
        var j = 0;
        $.each(CustomCountry, function(index1, value1) {
            $.each(InsCountryZone, function(index2, value2) {
                if((slimString(value1[1])) == slimString(value2[1])) {
                    same[i] = value1[1];
                    reflectionList[i] = [value1, value2];
                    i = i+1;
                }
            });
        });

        for(i=0; i<CustomCountry.length; i++) {
            var flag = compare(same,CustomCountry[i][1]);
            if(!flag) {
                diff_CustomCountry[j] = CustomCountry[i][1];
                j++;
            }
        }

        for(i=j=0; i<InsCountryZone.length; i++) {
            var flag_ = compare(same, InsCountryZone[i][1]);
            if(!flag_) {
                diff_InsCountryZone[j] = InsCountryZone[i][1];
                j++;
            }
        }
//        showDiff(diff_CustomCountry, diff_InsCountryZone);
        var fixed = fixSame(diff_CustomCountry, diff_InsCountryZone);

        for(i=0; i<fixed.length; i++) {
            for(j=0; j<CustomCountry.length; j++) {
                if(fixed[i][0] === CustomCountry[j][1]) {
                    foo[i] = [CustomCountry[j]];
                }
            }
            for(j=0; j<InsCountryZone.length; j++) {
                if(fixed[i][1] === InsCountryZone[j][1]) {
                    foo[i][1] = InsCountryZone[j]
                }
            }
        }

        var reflectionList_length = reflectionList.length;
        for(i=0; i<foo.length; i++) {
            reflectionList[reflectionList_length+i] = foo[i];
        }

        /* var test = [3,4];test2 = [2,3,4,5];
          loop = test2.length;
          for(i=0;i<loop; i++) {
              if(compare(test, test2[i])) {
                  removeVal(test2, test2[i]);
                  i = i-1;
                  loop = loop-1;
              }
          }
          console.log(test2);*/

        var temp_diff_CustomCountry = diff_CustomCountry;
        var loop = temp_diff_CustomCountry.length;
        console.log(dimension2_compare(fixed, '香港'));
        for(i=0; i<loop; i++) {
            if(dimension2_compare(fixed, temp_diff_CustomCountry[i])) {
                removeVal(temp_diff_CustomCountry, temp_diff_CustomCountry[i]);
                i = i-1;
                loop = loop -1;
            }
        }
        var ignore = temp_diff_CustomCountry;
        showArray(ignore);

//        showArray(reflectionList);//查看融合后的映射数组
//        console.log(reflectionList.length);


    }

    function slimString(value) {
        var temp_value = value;
        temp_value = temp_value.replace(/\s/g, '');
        temp_value = temp_value.replace(/（/g, '(');
        temp_value = temp_value.replace(/）/g, ')');
        return temp_value;
    }

    function compare (arr, value) {
        var i =0;
        for(i; i<arr.length; i++) {
            if(arr[i] === value) {
                return true;
            }
        }
        return false;
    }

    function dimension2_compare (arr, value) {
        var i = 0;
        var j = 0;
        for(i; i<arr.length; i++) {
            for(j; j<arr[i].length;j++) {
                if(arr[i][j] === value) {
                    return true;
                }
            }
        }
        return false;
    }

    function showArray(arr) {
        var i = 0;
        for(i;i<arr.length;i++) {
            console.log(i,arr[i]);
        }
    }

    function showDiff(arr1, arr2) {
        var length = arr1.length > arr2.length? arr1.length : arr2.length;
        for(var i=0; i<length; i++) {
            console.log(i,arr1[i], '..........',arr2[i]);
        }
    }

    function sliceArray(arr) {
        var arr_temp = arr;
        var temp = [];
        for(var i=0; i<arr.length; i++) {
            temp[i] = arr_temp[i][0];
        }
        showArray(temp);
        return temp;
    }

    //find diff name but same intrinsic val
    function fixSame(arr1, arr2) {
        var k=0;
        var like = [];
        var length = arr1.length < arr2.length? arr1.length : arr2.length;
        for(var i=0; i<length; i++) {
            var compare_1 = arr1[i].substring(0,2);

            for(var j=0; j<length; j++) {
                var compare_2 = arr2[j].substring(0,2);
                var compare_3 = arr2[j].slice(-2);
                if(compare_1 === compare_2) {
                    /*if(compare(sliceArray(like), arr1[i])) {
                        continue;
                    }*/
                    like[k] = [arr1[i],arr2[j]];
                    k++;
                }
                if(compare_1 === compare_3) {
                    like[k] = [arr1[i],arr2[j]];
                    k++;
                }
            }
        }
        console.log(like);
        return like;
    }

    //返回数组中某元素的index
    function indexOfVal(arr, val) {
        var loop = arr.length;
        console.log(val);
        for(var i=0; i<loop; i++) {
            if(arr[i] === val) {
                return i;
            }
        }
    }

    function removeVal(arr, val) {
        var index = indexOfVal(arr, val);
        if(index >= 0) {
            arr.splice(index, 1);
        }
    }

</script>
</body>
</html>