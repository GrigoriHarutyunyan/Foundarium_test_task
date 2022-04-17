<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{

    /**
     * Показ всех автомобилей.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $car = Car::all();
        if (count($car) <= 0) {
            return response()->json([
                'message' => 'В списке нет машин',
            ], 400);
        } else {
            return response()->json($car, 200);
        }
    }


    /**
     * Сохранение автомобиля в БД.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->only([
            'brand',
            'model',
        ]);
        $validator = Validator::make($data, [
            'brand' => 'required',
            'model' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $car = Car::create($data);
        return response()->json($car, 201);
    }


    /**
     *Изменение параметров автомобиля.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $data = $request->only([
            'brand',
            'model',
        ]);

        $validator = Validator::make($data, [
            'brand' => 'required',
            'model' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        try {
            Car::findOrFail($id)->update($data);
            return response()->json([
                'message' => "Автомобиль с id - $id обновлён.",
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => "Автомобиль с id - $id не найден для обновления.",
            ], 400);
        }
    }


    /**
     * Удаление автомобиля из списка.
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            Car::where('id', $id)
                ->firstOrFail()->delete();
            return response()->json([
                'message' => "Автомобиль с id - $id удален.",
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => "Автомобиль с id - $id не найден для удаления.",
            ], 400);
        }
    }

    /**
     * Проверяем если у автомобиля нет водителя и у водителя нет автомобиля то добавляем автомобилю водителя.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function addDriver(Request $request, $id): JsonResponse
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::with('car')->where('id', $data['user_id'])->first();
        if ($user  == null){
            return response()->json([
                'message' => "Водитель с id - ". $data["user_id"] . " не найден"
            ]);
        }
        try {
            $car = Car::with('user')->findOrFail($id);
            if ($car->user_id || isset($user->car)) {
                return response()->json([
                    'message' => "Автомобил или водител заняты",
                ], 201);
            } else {
                $car->update([
                    'user_id' => $data['user_id'],
                ]);
                return response()->json([
                    'message' => "У автомобилья бренда $car->brand появился водитель $user->name ",
                ], 201);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => "Автомобиль с id - $id не найден для добавления водителя.",
            ], 400);
        }
    }

    /**
     * Если у автомобиля есть водитель удаляем его.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function removeDriver(Request $request, $id): JsonResponse
    {
        $user_id = $request->user_id;

        try{
            $car = Car::with('user')->findOrFail($id);
            $user = User::with('car')->where('id', $user_id)->first();

            if($car->user_id && $car->user_id === $user->id){
                $car->update([
                    'user_id' => NULL,
                ]);
                return response()->json([
                    'message' => "Водитель $user->name покинул автомобил бренда $car->brand",
                ], 201);
            }else{
                return response()->json([
                    'message' => "У автомобиля с id - $id нет такого водителя.",
                ], 400);
            }
        }catch (Exception $e){
            return response()->json([
                'message' => "Автомобиль с id - $id не найден.",
            ], 400);
        }

    }
}
