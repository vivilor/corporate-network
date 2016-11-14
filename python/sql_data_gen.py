import codecs
import math
import string
import mysql.connector
import common_utilities as utils
from random import seed, randint

from datetime import datetime, date

tables = \
{
    'client':[
        'ID',
        'Name',
        'Birthday',
        'Passport',
        'ServicingStart',
        'Funds',
        'FundsSetDate',
        'Status',
        'StatusSetDate'
    ],
    'equip':[
        'ID',
        'Desc',
        'Cost'
    ],
    'service':[
        'ID',
        'Title',
        'Cost'
    ],
    'technitian':[
        'ID',
        'Name',
        'Salary'
    ],
    'order':[
        'ID',
        'Type',
        'ClientID',
        'ItemID',
        'Cost',
        'Amount',
        'TechnitianID',
        'Date'
    ],
    'client_service_relation':[
        'csrID',
        'csrClientID',
        'csrServiceID',
        'csrOrderDate',
        'csrStopDate'
    ],
    'client_equip_relation': [
        'cerID',
        'cerClientID',
        'cerEquipID',
        'cerOrderDate',
    ]
}

global_rows = \
    {
        'client':10000,
        'technitian':50
    }


class dateStorage:
    def __init__(self, day, month, year):
        self.dsDay = day
        self.dsMonth = month
        self.dsYear = year


    def copy(self):
        return dateStorage(self.dsDay, self.dsMonth, self.dsYear)


    def SQLformat(self):
        return utils.as_str(self.dsYear) + '-' + \
               utils.as_str(self.dsMonth) + '-' + \
               utils.as_str(self.dsDay)


    def isEarlierThan(self, date_to_check):
        if (self.dsYear < date_to_check.dsYear):
            return True
        elif (self.dsYear > date_to_check.dsYear):
            return False
        else:
            if (self.dsMonth < date_to_check.dsMonth):
                return True
            elif (self.dsMonth > date_to_check.dsMonth):
                return False
            else:
                if (self.dsDay < date_to_check.dsDay):
                    return True
                else:
                    return False


    def month_back(self):
        new_date = self.copy()
        if (self.dsMonth < 2):
            new_date.dsYear -= 1
            new_date.dsMonth = 12
            max_days = utils.days_of(new_date.dsMonth, new_date.dsYear)
            if (new_date.dsDay > max_days):
                new_date.dsDay = max_days
        else:
            new_date.dsMonth -= 1
        return new_date


    def SQLprint(self):
        print(self.SQLformat())


def get_cur_date():
    sys_date = datetime.now()
    return dateStorage(sys_date.day, sys_date.month, sys_date.year)


def gen_dates(date_start, date_stop, quantity):
    result = []

    for i in range(quantity):
        seed()

        gen_year = randint(date_start.dsYear, date_stop.dsYear)

        if gen_year == date_start.dsYear:
            gen_month = randint(date_start.dsMonth, 12)
        elif gen_year == date_stop.dsYear:
            gen_month = randint(1, date_stop.dsMonth)
        else:
            gen_month = randint(1, 12)

        if gen_month == date_start.dsMonth:
            gen_day = randint(date_start.dsDay,
                              utils.days_of(date_start.dsMonth,
                                            date_start.dsYear))
        elif gen_month == date_stop.dsMonth:
            gen_day = randint(1, date_stop.dsDay)
        else:
            gen_day = randint(1, utils.days_of(gen_month, gen_year))

        result.append(dateStorage(gen_day, gen_month, gen_year))

    return result


def gen_names(quantity, female_ratio):

    female_names        = utils.read_file_as_list_in_utf8('rus_names_female.txt')
    female_surnames     = utils.read_file_as_list_in_utf8('rus_surnames_female.txt')
    male_names          = utils.read_file_as_list_in_utf8('rus_names_male.txt')
    male_surnames       = utils.read_file_as_list_in_utf8('rus_surnames_male.txt')
    names_storage       = []

    for i in range(quantity):
        seed()
        if utils.check_random(female_ratio):
            name = female_names[randint(0, len(female_names) - 1)]
            surname = female_surnames[randint(0, len(female_surnames) - 1)]
        else:
            name = male_names[randint(0, len(male_names) - 1)]
            surname = male_surnames[randint(0, len(male_surnames) - 1)]
        names_storage.append(name + ' ' + surname)
    return names_storage


def gen_passports(quantity):
    seed()
    return list([randint(1171986301, 9829187581) for i in range(quantity)])


def query_ADD_FUNDS_TO_CLIENTS(mysql_instance, amount):
    cursor_handler = mysql_instance.cursor()

    cur_table = 'client'
    cur_column = cur_table + tables[cur_table][5]

    query = 'UPDATE ' + cur_table + \
              ' SET ' + cur_column + ' = ' + cur_column + ' + %s'
    args = (amount,)
    try:
        cursor_handler.execute(query, args)
        mysql_instance.commit()
    finally:
        cursor_handler.close()


'''
    Table: Clients

    +-----+----------------+-----------+--------+-------------------------------+--------------------------------+
    |INDEX|      TYPE      |GENERATING?|  TYPE  |           (100 - N)%          |                N%              |
    |-----+----------------+-----------+--------+-------------------------------+--------------------------------|
    |  -  | ID             |     -     |   --   |               --              |                --              |
    |     |                |           |        |                               |                                |
    |  0  | Name           |     +     | String |               --              |                --              |
    |  1  | Birthday       |     +     | Date   |               --              |                --              |
    |  2  | Passport       |     +     | Int    |               --              |                --              |
    |  3  | ServicingStart |     +     | Date   |               --              |                --              |
    |     |                |           |        |                               |                                |
    |  4  | Funds          |     +     | Float  |        [0, max_amount]        |         [0, max_amount]        |
    |  5  | FundsSetDate   |     +     | Date   | [cur_date - 30days, cur_date] | [cl_serv_start_date, cur_date]*|
    |     |                |           |        |                               |                                |
    |  6  | Status         |     +     | Int    |               1               |                0               |
    |  7  | StatusSetDate  |     +     | Date   |      cl_serv_start_date       | [cl_serv_start_date, cur_date]*|
    +-----+----------------+-----------+--------+-------------------------------+--------------------------------+

    Note: cells of rows marked by '*' will have the same values
'''
def gen_clients_data(quantity,
                     birth_date_start,
                     birth_date_stop,
                     servicing_start_date,
                     max_money_amount,
                     out_of_service_percent):

    clients_data = { tables['client'][i]:[]
                     for i in range(1, len(tables['client'])) }

    cur_date = get_cur_date()

    clients_data['Name'].extend(gen_names(quantity, 40))

    clients_data['Birthday'].extend(gen_dates(birth_date_start,
                                              birth_date_stop,
                                              quantity))

    clients_data['Passport'].extend(gen_passports(quantity))

    clients_data['ServicingStart'].extend(gen_dates(servicing_start_date,
                                                    cur_date,
                                                    quantity))

    for i in range(quantity):
        seed()
        current_funds = randint(0, max_money_amount - 1) + 0.00
        clients_data['Funds'].append(current_funds)

        if(utils.check_random(out_of_service_percent)):
            clients_data['Status'].append(0)

            funds_set_date = gen_dates(cur_date.month_back(),
                                       cur_date,
                                       1)
            status_set_date = funds_set_date
        else:
            clients_data['Status'].append(1)

            funds_set_date = gen_dates(cur_date.month_back(),
                                       cur_date,
                                       1)
            status_set_date = gen_dates(clients_data['ServicingStart'][i],
                                        cur_date,
                                        1)

        clients_data['FundsSetDate'].extend(funds_set_date)
        clients_data['StatusSetDate'].extend(status_set_date)

    '''
    for i in range(quantity):
        info = ''
        info += clients_data['Name'][i]
        infolen = len(info)
        for j in range(10 - int(infolen / 4)):
            info += '\t'
        info += clients_data['Birthday'][i].SQLformat()
        info += '\t'
        info += str(clients_data['Passport'][i])
        info += '\t'
        info += clients_data['ServicingStart'][i].SQLformat()
        info += '\t'
        info += str(clients_data['Funds'][i])
        info += '\t'
        info += clients_data['FundsSetDate'][i].SQLformat()
        info += '\t'
        info += str(clients_data['Status'][i])
        info += '\t'
        info += clients_data['StatusSetDate'][i].SQLformat()
        print(info)
    '''
    return clients_data

def gen_service_data():
    services_data = { tables['service'][i]:[]
                     for i in range(1, len(tables['service'])) }
    types = ['C', 'B', 'A', 'S', 'S+']

    for i in range(5):
        services_data['Title'].append('LIMIT ' + types[i])
        services_data['Cost'].append(99 + i * (20+i*10))

    for i in range(3):
        services_data['Title'].append('TV ' + types[i])
        services_data['Cost'].append(69 + 10 * (i*10))

    for i in range(5):
        services_data['Title'].append('UNLIMIT ' + types[i])
        services_data['Cost'].append(199 + 50 * i)

    for i in range(5+3+5):
        info = ''
        info += services_data['Title'][i]
        infolen = len(info)
        for j in range(6 - int(infolen / 4)):
            info += '\t'
        info += str(services_data['Cost'][i])
        print(info)

    return services_data


def gen_equip_data():
    manufacturers = [
        "D-Link",
        "Asus",
        "TP-Link",
        "ZyXEL",
        "Linksys",
        "Belkin",
        "Netgear",
        "ZTE"]
    equip_data = { tables['equip'][i]: []
                   for i in range(1, len(tables['equip'])) }

    for i in range(len(manufacturers)):
        equip_data['Desc'].append('Маршрутизатор ' + manufacturers[i])
        equip_data['Cost'].append(700 + randint(0, 12) * 100)

    for i in range(len(manufacturers)):
        equip_data['Desc'].append('Коммутатор ' + manufacturers[i])
        equip_data['Cost'].append(700 + randint(0, 12) * 100)

    equip_data['Desc'].append('Патчкорд / RJ-45 / 1m')
    equip_data['Cost'].append(20)

    for i in range(len(manufacturers) * 2 + 1):
        info = ''
        info += equip_data['Desc'][i]
        infolen = len(info)
        for j in range(8 - int(infolen / 4)):
            info += '\t'
        info += str(equip_data['Cost'][i])
        print(info)

    return equip_data


def gen_technitian_data(quantity,
                        salary_full):
    technitian_data = { tables['technitian'][i]:[]
                        for i in range(1, len(tables['technitian'])) }
    technitian_data['Name'].extend(gen_names(quantity, 0))
    for i in range(quantity):
        salary = 0.00
        if utils.check_random(70):
            salary += salary_full
        else:
            salary += (salary_full / 2)
        technitian_data['Salary'].append(salary)

    for i in range(quantity):
        info = ''
        info += technitian_data['Name'][i]
        infolen = len(info)
        for j in range(8 - int(infolen / 4)):
            info += '\t'
        info += str(technitian_data['Salary'][i])
        print(info)

    return technitian_data

def gen_orders_and_relations(quantity,
                             clients,
                             services,
                             equip,
                             technitians):
    services_set = len(services['Cost'])-1
    equip_set = len(equip['Cost'])-1
    technitians_set = len(technitians['Salary'])-1

    orders_data = {tables['order'][i]: []
                     for i in range(1, len(tables['order']))}
    csr_data = {tables['client_service_relation'][i]: []
                     for i in range(1, len(tables['client_service_relation']))}
    cer_data = {tables['client_equip_relation'][i]: []
                     for i in range(1, len(tables['client_equip_relation']))}

    for i in range(quantity):
        seed()

        if utils.check_random(20):
            item_id = randint(0, equip_set)
            order_amount = randint(3, 40) if(item_id == equip_set) else 1
            order_cost = order_amount * equip['Cost'][item_id]
            technitian_id = randint(0, technitians_set)

            orders_data['Type'].append(1)
            orders_data['ClientID'].append(i+1)
            orders_data['ItemID'].append(item_id+1)
            orders_data['Cost'].append(order_cost)
            orders_data['Amount'].append(order_amount)
            orders_data['TechnitianID'].append(technitian_id+1)
            orders_data['Date'].append(clients['ServicingStart'][i])

            cer_data['cerClientID'].append(i+1)
            cer_data['cerEquipID'].append(item_id)
            cer_data['cerOrderDate'].append(clients['ServicingStart'][i])


        item_id = randint(0, services_set)
        order_cost = services['Cost'][item_id]

        orders_data['Type'].append(0)
        orders_data['ClientID'].append(i+1)
        orders_data['ItemID'].append(item_id+1)
        orders_data['Cost'].append(order_cost)
        orders_data['Amount'].append(1)
        orders_data['TechnitianID'].append(None)
        orders_data['Date'].append(clients['ServicingStart'][i])

        csr_data['csrClientID'].append(i+1)
        csr_data['csrServiceID'].append(item_id+1)
        csr_data['csrOrderDate'].append(clients['ServicingStart'][i])
        if not clients['Status'][i]:
            csr_data['csrStopDate'].append(clients['StatusSetDate'][i])
        else:
            csr_data['csrStopDate'].append(None)

    return [orders_data, csr_data, cer_data]
            

a = dateStorage(1, 1, 1960)
b = dateStorage(31, 12, 1997)
start_date = dateStorage(1, 6, 2010)
'''
clients_data = gen_clients_data(global_rows['client'], a, b, start_date, 199, 5)
technitian_data = gen_technitian_data(global_rows['technitian'], 50000)
service_data = gen_service_data()
equip_data = gen_equip_data()

arr = gen_orders_and_relations(global_rows['client'],
                               clients_data,
                               service_data,
                               equip_data,
                               technitian_data)

for i in range(len(arr[0]['Type'])):
    info = ''
    info += str(arr[0]['Type'][i])
    for j in range(10 - len(info)):
        info += ' '
    info += str(arr[0]['ClientID'][i])
    for j in range(20 - len(info)):
        info += ' '
    info += str(arr[0]['ItemID'][i])
    for j in range(30 - len(info)):
        info += ' '
    info += str(arr[0]['Cost'][i])
    for j in range(40 - len(info)):
        info += ' '
    info += str(arr[0]['Amount'][i])
    for j in range(50 - len(info)):
        info += ' '
    if(arr[0]['TechnitianID'][i]):
        info += str(arr[0]['TechnitianID'][i])
    for j in range(60 - len(info)):
        info += ' '
    info += arr[0]['Date'][i].SQLformat()
    info += '\t'
    print(info)
'''
