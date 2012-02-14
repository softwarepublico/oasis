	/* --------- SICON GANTT CHART -----------------------------------------------------------
	 * AUTHOR		: Dathq - ICT Service Engineering Jsc, - dathq@ise.com.vn
	 * LICENSE		: Free
	 * DESCRIPTION	: Create a new task item with these info
	 *		- from: start date (format: mm/dd/dddd)
	 *		- to: deadline of task (format: mm/dd/dddd)
	 *		- task: name of the task, what has to be solved (not includes ')
	 *		- resource: who have to solve this task (not includes ')
	 *		- progress: how is it going? (format: integer value from 0 to 100, not includes %)
	 *----------------------------------------------------------------------------------------*/
	function Task(from, to, task, resource, progress, nivel)
	{
		var _from = new Date();
		var _to = new Date();
		var _task = task;
		var _resource = resource;
		var _progress = progress;
		var _nivel = (nivel)?"_"+nivel:"_3";

		var dvArr = from.split('/');
		_from.setFullYear(parseInt(dvArr[2], 10), parseInt(dvArr[0], 10) - 1, parseInt(dvArr[1], 10));
		dvArr = to.split('/');
		_to.setFullYear(parseInt(dvArr[2], 10), parseInt(dvArr[0], 10) - 1, parseInt(dvArr[1], 10));

		this.getFrom = function(){ return _from};
		this.getTo = function(){ return _to};
		this.getTask = function(){ return _task};
		this.getResource = function(){ return _resource};
		this.getProgress = function(){ return _progress};
		this.getNivel = function(){ return _nivel };
	}

	function Gantt(gDiv,title)
	{
		var _title = (title)?title:"Tarefa";
		var _GanttDiv = gDiv;
		var _taskList = new Array();
		this.AddTaskDetail = function(value)
		{
			_taskList.push(value);
		}
		this.Draw = function()
		{
			var _offSet = 0;
			var _dateDiff = 0;
			var _currentDate = new Date();
			var _maxDate = new Date();
			var _minDate = new Date();
			var _dTemp = new Date();
			var _firstRowStr = "<table border=1 style='border-collapse:collapse'><tr><td rowspan='2'><div class='GTaskTitle' style='width:250px;' title='"+ title +"'>"+ textLong(title) +"</div></td>";
			var _thirdRow = "";
			var _gStr = "";
			var _colSpan = 0;
			var counter = 0;
			//Variaveis adicionadas pelo Wunilberto Melo 19/08/2009
			var space = "";
			var textTask = "";

			_currentDate.setFullYear(_currentDate.getFullYear(), _currentDate.getMonth(), _currentDate.getDate());
			if(_taskList.length > 0)
			{
				_maxDate.setFullYear(_taskList[0].getTo().getFullYear(), _taskList[0].getTo().getMonth(), _taskList[0].getTo().getDate());
				_minDate.setFullYear(_taskList[0].getFrom().getFullYear(), _taskList[0].getFrom().getMonth(), _taskList[0].getFrom().getDate());
				for(i = 0; i < _taskList.length; i++)
				{
					if(Date.parse(_taskList[i].getFrom()) < Date.parse(_minDate))
						_minDate.setFullYear(_taskList[i].getFrom().getFullYear(), _taskList[i].getFrom().getMonth(), _taskList[i].getFrom().getDate());
					if(Date.parse(_taskList[i].getTo()) > Date.parse(_maxDate))
						_maxDate.setFullYear(_taskList[i].getTo().getFullYear(), _taskList[i].getTo().getMonth(), _taskList[i].getTo().getDate());
				}

				//---- Fix _maxDate value for better displaying-----
				// Add at least 5 days

				if(_maxDate.getMonth() == 11) //December
				{
					if(_maxDate.getDay() + 5 > getDaysInMonth(_maxDate.getMonth() + 1, _maxDate.getFullYear()))
						_maxDate.setFullYear(_maxDate.getFullYear() + 1, 1, 5); //The fifth day of next month will be used
					else
						_maxDate.setFullYear(_maxDate.getFullYear(), _maxDate.getMonth(), _maxDate.getDate() + 5); //The fifth day of next month will be used
				}
				else
				{
					if(_maxDate.getDay() + 5 > getDaysInMonth(_maxDate.getMonth() + 1, _maxDate.getFullYear()))
						_maxDate.setFullYear(_maxDate.getFullYear(), _maxDate.getMonth() + 1, 5); //The fifth day of next month will be used
					else
						_maxDate.setFullYear(_maxDate.getFullYear(), _maxDate.getMonth(), _maxDate.getDate() + 5); //The fifth day of next month will be used
				}

				//--------------------------------------------------

				_gStr = "";
				_gStr += "</tr><tr>";
				_thirdRow = "<tr><td>&nbsp;</td>";
				_dTemp.setFullYear(_minDate.getFullYear(), _minDate.getMonth(), _minDate.getDate());
				while(Date.parse(_dTemp) <= Date.parse(_maxDate))
				{
					if(_dTemp.getDay() % 6 == 0) //Weekend
					{
						_gStr += "<td class='GWeekend'><div style='width:24px;'>" + _dTemp.getDate() + "</div></td>";
						if(Date.parse(_dTemp) == Date.parse(_currentDate))
							_thirdRow += "<td id='GC_" + (counter++) + "' class='GToDay' style='height:" + ((_taskList.length * 21) + 10 ) + "'>&nbsp;</td>";
						else
							_thirdRow += "<td id='GC_" + (counter++) + "' class='GWeekend' style='height:" + ((_taskList.length * 21) + 10 ) + "'>&nbsp;</td>";
					}
					else
					{
						_gStr += "<td class='GDay'><div style='width:24px;'>" + _dTemp.getDate() + "</div></td>";
						if(Date.parse(_dTemp) == Date.parse(_currentDate))
							_thirdRow += "<td id='GC_" + (counter++) + "' class='GToDay' style='height:" + ((_taskList.length * 21) + 10 ) + "'>&nbsp;</td>";
						else
							_thirdRow += "<td id='GC_" + (counter++) + "' class='GDay' style='height:" + ((_taskList.length * 21) + 10 ) + "'>&nbsp;</td>";
					}
					if(_dTemp.getDate() < getDaysInMonth(_dTemp.getMonth() + 1, _dTemp.getFullYear()))
					{
						if(Date.parse(_dTemp) == Date.parse(_maxDate))
						{
							_firstRowStr += "<td class='GMonth' colspan='" + (_colSpan + 1) + "'>" + getTextMonth((_dTemp.getMonth() + 1)) + "/" + _dTemp.getFullYear() + "</td>";
						}
						_dTemp.setDate(_dTemp.getDate() + 1);
						_colSpan++;
					}
					else
					{
						_firstRowStr += "<td class='GMonth' colspan='" + (_colSpan + 1) + "'>" + getTextMonth((_dTemp.getMonth() + 1)) + "/" + _dTemp.getFullYear() + "</td>";
						_colSpan = 0;
						if(_dTemp.getMonth() == 11) //December
						{
							_dTemp.setFullYear(_dTemp.getFullYear() + 1, 0, 1);
						}
						else
						{
							_dTemp.setFullYear(_dTemp.getFullYear(), _dTemp.getMonth() + 1, 1);
						}
					}
				}
				_thirdRow += "</tr>";
				_gStr += "</tr>" + _thirdRow;
				_gStr += "</table>";
				_gStr = _firstRowStr + _gStr;
				for(i = 0; i < _taskList.length; i++)
				{
					//Condição incluida por Wunilberto Melo 19/08/2009
					if(_taskList[i].getNivel() == "_2"){
						space = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					} else if(_taskList[i].getNivel() == "_3"){
						space = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					}
					textTask = space + textLong(_taskList[i].getTask());
					//--
					_offSet = (Date.parse(_taskList[i].getFrom()) - Date.parse(_minDate)) / (24 * 60 * 60 * 1000);
					_dateDiff = (Date.parse(_taskList[i].getTo()) - Date.parse(_taskList[i].getFrom())) / (24 * 60 * 60 * 1000) + 1;
					_gStr += "<div style='position:absolute; top:" + ((20 * (i + 2))+130) + "; left:" + ((_offSet * 27 + 254)+ 8) + "; width:" + (27 * _dateDiff - 1 + 100) + "'><div title='" + _taskList[i].getTask() + "' class='GTask"+ _taskList[i].getNivel() +"' style='float:left; width:" + (27 * _dateDiff - 1) + "px;'>" + getProgressDiv(_taskList[i].getProgress()) + "</div><div style='float:left; padding-left:3'>" + _taskList[i].getResource() + "</div></div>";
					_gStr += "<div style='position:absolute; top:" + ((20 * (i + 2) + 1)+130) + "; left:15px' class='fontNivel"+ _taskList[i].getNivel() +"' title='"+_taskList[i].getTask()+"'>" + textTask + "</div>";
				}
				_GanttDiv.innerHTML = _gStr;
			}
		}
	}

	function getProgressDiv(progress)
	{
		return "<div class='GProgress' style='width:" + progress + "%; overflow:hidden'></div>"
	}
	// GET NUMBER OF DAYS IN MONTH
	function getDaysInMonth(month, year)
	{

		var days;
		switch(month)
		{
			case 1:
			case 3:
			case 5:
			case 7:
			case 8:
			case 10:
			case 12:
				days = 31;
				break;
			case 4:
			case 6:
			case 9:
			case 11:
				days = 30;
				break;
			case 2:
				if (((year% 4)==0) && ((year% 100)!=0) || ((year% 400)==0))
					days = 29;
				else
					days = 28;
				break;
		}
		return (days);
	}

	function getTextMonth(month) {

		var textMonth;
		switch(month)
		{
			case 1:
				textMonth = "Jan";
				break;
			case 2:
				textMonth = "Fev";
				break;
			case 3:
				textMonth = "Mar";
				break;
			case 4:
				textMonth = "Abr";
				break;
			case 5:
				textMonth = "Mai";
				break;
			case 6:
				textMonth = "Jun";
				break;
			case 7:
				textMonth = "Jul";
				break;
			case 8:
				textMonth = "Ago";
				break;
			case 9:
				textMonth = "Set";
				break;
			case 10:
				textMonth = "Out";
				break;
			case 11:
				textMonth = "Nov";
				break;
			case 12:
				textMonth = "Dez";
				break;
		}
		return(textMonth);
	}

	function textLong(text, tam)
	{
		var tam = (tam)?tam:25;
		var newText = "";
		if(text.length >= tam){
			newText = text.substr(0, tam)+"...";
		} else {
			newText = text;
		}
		return newText;
	}