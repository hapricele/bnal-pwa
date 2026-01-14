#include <iostream>
#include <fstream>
#include <string>
#include <cstdlib>

int main(int argc, char* argv[]) {
    std::ofstream err("error.txt");
    if (!err.is_open()) {
        std::cerr << "Ошибка создания error.txt" << std::endl;
        return 1;
    }

    int a = 0, b = 0;
    std::string estr;
    bool has_a = false, has_b = false, has_e = false;

    // Ручной парсинг argv
    for (int i = 1; i < argc; ++i) {
        std::string arg = argv[i];
        if (arg.size() >= 2 && arg[0] == '-') {
            char key = arg[1];
            std::string value = (i + 1 < argc) ? argv[i + 1] : "";
            if (key == 'a' && !value.empty()) {
                a = std::atoi(value.c_str());
                has_a = true;
                ++i;
            } else if (key == 'b' && !value.empty()) {
                b = std::atoi(value.c_str());
                has_b = true;
                ++i;
            } else if (key == 'e' && !value.empty()) {
                estr = value;
                has_e = true;
                ++i;
            } else {
                err << "Неизвестный или неверный ключ: " << arg << std::endl;
                return 1;
            }
        } else {
            err << "Неверный аргумент: " << arg << std::endl;
            return 1;
        }
    }

    if (!has_a || !has_b || !has_e) {
        err << "Требуются ключи -a N -b M -e str" << std::endl;
        return 1;
    }

    if (estr.length() > 40) {
        err << "Строка str превышает 40 символов" << std::endl;
        return 1;
    }

    // Вывод: str: цифры N с 3 пробелами
    std::cout << estr << ": ";
    std::string n_str = std::to_string(std::abs(a));
    for (size_t j = 0; j < n_str.length(); ++j) {
        std::cout << n_str[j];
        if (j < n_str.length() - 1) std::cout << "   ";
    }
    std::cout << std::endl;

    // Вторая строка: цифры M с 3 пробелами
    std::string m_str = std::to_string(std::abs(b));
    for (size_t j = 0; j < m_str.length(); ++j) {
        std::cout << m_str[j];
        if (j < m_str.length() - 1) std::cout << "   ";
    }
    std::cout << std::endl;

    return 0;
}
