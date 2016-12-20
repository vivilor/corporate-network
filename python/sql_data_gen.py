import codecs
import math
import string
import mysql.connector
import common_utilities as utils
from random import seed, randint


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


from datetime import datetime, date

tables = \
{
    'client':[
        'ID',
        'Name',
        'Birthday',
        'PassportSerial',
        'PassportNumber',
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

    def add_year(self):
        self.dsYear += 1

    def add_month(self):
        if self.dsMonth == 12:
            self.dsMonth = 1
            self.add_year()
        else:
            self.dsMonth += 1

    def add_day(self):
        if self.dsDay == utils.days_of(self.dsMonth, self.dsYear):
            self.dsDay = 1
            self.add_month()
        else:
            self.dsDay += 1


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


def to_int(str):
    n = 0
    power = 0
    for i in range(len(str)-1, -1, -1):
        n += int(str[i]) * 10 ** power
        power += 1
    return n


def to_dS(date_str):
    return dateStorage(to_int(date_str[8:10]),
                       to_int(date_str[5:7]),
                       to_int(date_str[0:4]))


def get_cur_date():
    sys_date = datetime.now()
    return dateStorage(sys_date.day, sys_date.month, sys_date.year)


def compare_date(dateA, dateB):
    if dateA.dsYear > dateB.dsYear:
        return 1
    elif dateA.dsYear < dateB.dsYear:
        return -1
    else:
        if dateA.dsMonth > dateB.dsMonth:
            return 1
        if dateA.dsMonth < dateB.dsMonth:
            return -1
        else:
            if dateA.dsDay > dateB.dsDay:
                return 1
            if dateA.dsDay < dateB.dsDay:
                return -1
            else:
                return 0


def oldest_date_index(dates):
    oldest = dates[0]
    index = 0
    for i in range(len(dates)):
        date = dates[i]
        if compare_date(oldest, date) > 0:
            index = i
            oldest = date
    return index


def gen_date(start, stop):

    if not compare_date(start, stop):
        return start
    if start.dsYear == stop.dsYear:
        new_year = start.dsYear
        if start.dsMonth == stop.dsMonth:
            new_month = start.dsMonth
            new_day = randint(start.dsDay, stop.dsDay)
        else:
            new_month = randint(start.dsMonth, stop.dsMonth)
            if new_month == start.dsMonth:
                new_day = randint(start.dsDay,
                                   utils.days_of(start.dsMonth,
                                                 start.dsYear))
            elif new_month == stop.dsMonth:
                new_day = randint(1,
                                   stop.dsDay)
            else:
                new_day = randint(1,
                                   utils.days_of(new_month,
                                                 new_year))
    else:
        new_year = randint(start.dsYear, stop.dsYear)
        if new_year == start.dsYear:
            new_month = randint(start.dsMonth, 12)
            if new_month == start.dsMonth:
                new_day = randint(start.dsDay,
                                  utils.days_of(new_month,
                                                new_year))
            else:
                new_day = randint(1,
                                  utils.days_of(new_month,
                                                new_year))
        elif new_year == stop.dsYear:
            new_month = randint(1, stop.dsMonth)
            if new_month == stop.dsMonth:
                new_day = randint(1,
                                  stop.dsDay)
            else:
                new_day = randint(1,
                                  utils.days_of(new_month,
                                                new_year))
        else:
            new_month = randint(1, 12)
            new_day = randint(1,
                              utils.days_of(new_month,
                                            new_year))

    return dateStorage(new_day, new_month, new_year)


def gen_dates(date_start, date_stop, quantity):
    seed()
    return list(gen_date(date_start, date_stop) for i in range(quantity))

def gen_sorted_dates(date_start, date_stop, quantity):
    storage = []
    i = 0
    while i < quantity:
        new_date = gen_date(date_start, date_stop)
        if not len(storage):
            storage.append(new_date)
        elif len(storage) == 1:
            if compare_date(new_date, storage[0]) == 1:
                storage.append(new_date)
            else:
                storage.insert(0, new_date)
        else:
            for j in range(len(storage)):
                if compare_date(new_date, storage[j]) < 1:
                    storage.insert(j, new_date)
                    break
                else:
                    if j == len(storage)-1:
                        storage.append(new_date)
                        break
        i += 1
    return storage

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


def gen_passports_serial(quantity):
    seed()
    return list([randint(1001, 9817) for i in range(quantity)])


def gen_passports_number(quantity):
    seed()
    return list([randint(100001, 989189) for i in range(quantity)])


def gen_clients(quantity,
                    serv_start,
                    max_funds,
                    defaulters_percent):
    birth_start = dateStorage(1, 1, 1960)
    birth_stop = dateStorage(31, 12, 1997)
    cur_date = get_cur_date()
    female_percent = 40
    storage = { tables['client'][i]:[] for i in range(1, len(tables['client'])) }

    storage['Name'].extend(gen_names(quantity, female_percent))
    storage['Birthday'].extend(gen_dates(birth_start, birth_stop, quantity))
    storage['PassportSerial'].extend(gen_passports_serial(quantity))
    storage['PassportNumber'].extend(gen_passports_number(quantity))
    storage['ServicingStart'].extend(gen_sorted_dates(serv_start, cur_date, quantity))

    for i in range(quantity):
        seed()

        mode = utils.check_random(defaulters_percent)
        current_funds = randint(0, max_funds - 1) + 0.00
        service_start = storage['ServicingStart'][i]

        storage['Funds'].append(current_funds)
        storage['Status'].append(0 if mode else 1)

        if compare_date(service_start, cur_date.month_back()) < 1:
            funds_set_start = cur_date.month_back()
        else:
            funds_set_start = service_start

        funds_set_date = gen_dates(funds_set_start, cur_date, 1)

        if(mode):
            status_set_date = funds_set_date
        else:
            status_set_date = gen_dates(service_start, cur_date, 1)

        storage['FundsSetDate'].append(funds_set_date[0].SQLformat())
        storage['StatusSetDate'].append(status_set_date[0].SQLformat())
        storage['Birthday'][i] = storage['Birthday'][i].SQLformat()
        storage['ServicingStart'][i] = service_start.SQLformat()
    print('Successfully generated ' + str(quantity) + ' clients.')
    return storage


def swap(data, i, j):
    tmp = data[i]
    data[i] = data[j]
    data[j] = tmp


def sort_dates(raw_data):
    while True:
        counter = 0
        swapped = False
        for i in range(len(raw_data)-1):
            result = compare_date(raw_data[i], raw_data[i+1])
            if result == 1:
                swap(raw_data, i, i+1)
                swapped = True
                counter += 1
            else:
                continue
        print('Swapped: ' + str(counter))
        if not swapped:
            return
    return raw_data


def print_clients(clients):
    f = codecs.open("table.txt", "w", "utf-8")

    for i in range(global_rows['client']):
        info = ''
        width = 30
        for key in tables['client']:
            if key == 'ID': continue
            info += str(clients[key][i])
            for j in range(width - len(info)):
                info += ' '
            width += 12
        print(info)
        info += '\n'
        f.write(info)


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
    '''
    for i in range(5+3+5):
        info = ''
        info += services_data['Title'][i]
        infolen = len(info)
        for j in range(6 - int(infolen / 4)):
            info += '\t'
        info += str(services_data['Cost'][i])
        print(info)
    '''
    print('Successfully generated ' + str(5+3+5) + ' services.')
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
    '''
    for i in range(len(manufacturers) * 2 + 1):
        info = ''
        info += equip_data['Desc'][i]
        infolen = len(info)
        for j in range(8 - int(infolen / 4)):
            info += '\t'
        info += str(equip_data['Cost'][i])
        print(info)
    '''
    print('Successfully generated ' + str(len(equip_data)) + ' equipment units.')
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
    '''
    for i in range(quantity):
        info = ''
        info += technitian_data['Name'][i]
        infolen = len(info)
        for j in range(8 - int(infolen / 4)):
            info += '\t'
        info += str(technitian_data['Salary'][i])
        print(info)
    '''
    print('Successfully generated ' + str(quantity) + ' technitians.')
    return technitian_data

def gen_orders(clients,
               services,
               equip,
               technitians):
    services_set = len(services['Cost'])-1
    equip_set = len(equip['Cost'])-1
    technitians_set = len(technitians['Salary'])-1

    orders_data = {tables['order'][i]: []
                     for i in range(1, len(tables['order']))}
    quantity = len(clients['Name'])
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

        item_id = randint(0, services_set)
        order_cost = services['Cost'][item_id]

        orders_data['Type'].append(0)
        orders_data['ClientID'].append(i+1)
        orders_data['ItemID'].append(item_id+1)
        orders_data['Cost'].append(order_cost)
        orders_data['Amount'].append(1)
        orders_data['TechnitianID'].append(None)
        orders_data['Date'].append(clients['ServicingStart'][i])

    print('Successfully generated ' + str(len(orders_data)) + ' orders.')

    table_info = tables['order']

    f = codecs.open("orders.txt", "w", "utf-8")

    for i in range(len(orders_data['Cost'])):
        query_text = """INSERT INTO `order`("""
        for j in range(1, len(table_info)):
            if (orders_data[table_info[j]][i] == None) and j == 6:
                continue
            query_text += """order"""
            query_text += table_info[j]


            if j == (len(table_info) - 1):
                query_text += """) """
            else:
                query_text += """, """

        query_text += """ VALUES( """
        for j in range(1, len(table_info)):
            if (j == 7):
                query_text += '\''
            if (orders_data[table_info[j]][i] == None):
                continue
            query_text += str(orders_data[table_info[j]][i])
            if (j == 7):
                query_text += '\''
            if j == (len(table_info) - 1):
                query_text += """); """
            else:
                query_text += """, """
        query_text += '\n'
        f.write(query_text)
    f.close()
    return orders_data
            
start_date = dateStorage(1, 6, 2010)



def gen_tatoo_clients():
    streets_moscow = utils.read_file_as_list_in_utf8('streets_moscow.txt')
    streets_len = len(streets_moscow)-1

    birth_start = dateStorage(1, 1, 1960)
    birth_end = dateStorage(31, 12, 1998)

    output_file = codecs.open('tatoo_clients_query.sql', 'w', 'utf-8')
    table_columns = ['Name', 'Birthday', 'Phone_number', 'Address']

    query_part = "INSERT INTO `tatoo-salon`.`clients`("
    for col in table_columns:
        query_part += (col + ', ')

    query_part = query_part[:-2]
    query_part += ') VALUES('


    for i in range(2000):
        query_gen_part = '\''
        query_gen_part += gen_names(1, 70)[0]
        query_gen_part += '\', \''
        query_gen_part += gen_date(birth_start, birth_end).SQLformat()
        query_gen_part += '\', '
        query_gen_part += '89'
        query_gen_part += str(randint(191218276, 899948975))
        query_gen_part += ', \''
        query_gen_part += streets_moscow[randint(0, streets_len)]
        query_gen_part += '\');\n'
        output_file.write(query_part + query_gen_part)
        print(i)


def gen_schedules():
    table_columns = ['P_id', 'D_id', 'Record_date', 'Room']
    service_start = dateStorage(5, 7, 2010)
    service_end = dateStorage(1, 2, 2017)
    output_file = codecs.open('tatoo_schedules_query.sql', 'w', 'utf-8')

    query_part = "INSERT INTO `tatoo-salon`.`schedule`("
    for col in table_columns:
        query_part += (col + ', ')

    dates = gen_sorted_dates(service_start, service_end, 2000)

    query_part = query_part[:-2]
    query_part += ') VALUES('
    n = 1
    record_date = dateStorage(5, 7, 2014)
    while True:

        record_num = randint(0, 6)
        masters_time = [ [] for i in range(3) ]

        for i in range(0, record_num):
            master_id = randint(1, 3)
            query_gen_part = ''
            query_gen_part += str(randint(1, 2000))
            query_gen_part += ', '
            query_gen_part += str(master_id)
            query_gen_part += ', \''
            query_gen_part += record_date.SQLformat()
            query_gen_part += ' '

            if(master_id == 1):
                busy_time = 9 + 2 * randint(0, 2)
                for free_time in masters_time[0] :
                    if(free_time != busy_time):
                        print(">> 1")
                        masters_time[0].append(busy_time)
                        break
                query_gen_part += \
                    '0' + str(busy_time) if busy_time < 10 else str(busy_time)
            if(master_id == 2):
                busy_time = 12 + 2 * randint(0, 2)
                for free_time in masters_time[1]:
                    if (free_time != busy_time):
                        print(">> 2")
                        masters_time[1].append(busy_time)
                        break
                query_gen_part += str(busy_time)
            if(master_id == 3):
                busy_time = 15 + 2 * randint(0, 2)
                for free_time in masters_time[2]:
                    if (free_time != busy_time):
                        print(">> 3")
                        masters_time[2].append(busy_time)
                        break
                query_gen_part += str(busy_time)

            query_gen_part += ':00:00\', '
            query_gen_part += str(master_id)
            query_gen_part += ');\n'
            output_file.write(query_part + query_gen_part)
            print('Created row #' + str(n))
            n += 1
        record_date.add_day()
        if(not compare_date(record_date, service_end)):
            return



gen_tatoo_clients()
#gen_schedules()
'''
a = dateStorage(1, 1, 1960)
b = dateStorage(31, 12, 1997)


dates = gen_sorted_dates(a, b, 100)

#f = codecs.open("table.txt", "w", "utf-8")
for datestamp in dates:
    datestamp.SQLprint()

print(len(dates))
    #f.write(datestamp.SQLformat() + '\n')


clients_data = gen_clients(global_rows['client'],
                               start_date,
                               199,
                               5)

technitian_data = gen_technitian_data(global_rows['technitian'], 50000)

service_data = gen_service_data()

equip_data = gen_equip_data()
'''

