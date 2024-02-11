<?php

namespace App\ContohBootcamp\Services;

use App\ContohBootcamp\Repositories\TaskRepository;

class TaskService
{
	private TaskRepository $taskRepository;

	public function __construct()
	{
		$this->taskRepository = new TaskRepository();
	}

	/**
	 * NOTE: untuk mengambil semua tasks di collection task
	 */
	public function getTasks()
	{
		$tasks = $this->taskRepository->getAll();
		return $tasks;
	}

	/**
	 * NOTE: menambahkan task
	 */
	public function addTask(array $data)
	{
		$taskId = $this->taskRepository->create($data);
		return $taskId;
	}

	/**
	 * NOTE: UNTUK mengambil data task
	 */
	public function getById(string $taskId)
	{
		$task = $this->taskRepository->getById($taskId);
		return $task;
	}

	/**
	 * NOTE: untuk update task
	 */
	public function updateTask(array $editTask, array $formData)
	{
		if (isset($formData['title'])) {
			$editTask['title'] = $formData['title'];
		}

		if (isset($formData['description'])) {
			$editTask['description'] = $formData['description'];
		}

		if (isset($formData['assigned'])) {
			$editTask['assigned'] = $formData['assigned'];
		}

		$id = $this->taskRepository->save($editTask);
		return $id;
	}

	/**
	 * NOTE: UNTUK menghapus data task
	 */
	public function deleteById(string $taskId)
	{
		$id = $this->taskRepository->deleteById($taskId);
		return $id;
	}

	/**
	 * NOTE: untuk tambah subtask
	 */
	public function addSubTask(array $editTask, array $formData)
	{

		$subtasks = isset($editTask['subtasks']) ? $editTask['subtasks'] : [];

		$subtasks[] = [
			'_id' => (string) new \MongoDB\BSON\ObjectId(),
			'title' => $formData['title'],
			'description' => $formData['description']
		];

		$editTask['subtasks'] = $subtasks;

		$id = $this->taskRepository->save($editTask);
		return $id;
	}

	/**
	 * NOTE: untuk hapus subtask
	 */
	public function deleteSubTask(array $editTask, string $subTaskId)
	{
		$subtasks = isset($editTask['subtasks']) ? $editTask['subtasks'] : [];

		// Pencarian dan penghapusan subtask
		$subtasks = array_filter($subtasks, function ($subtask) use ($subTaskId) {
			if ($subtask['_id'] == $subTaskId) {
				return false;
			} else {
				return true;
			}
		});
		$subtasks = array_values($subtasks);
		$editTask['subtasks'] = $subtasks;

		$id = $this->taskRepository->save($editTask);
		return $id;
	}
}
