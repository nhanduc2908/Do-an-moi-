import 'dart:convert';
import '../models/assessment_model.dart';
import '../models/api_response_model.dart';
import '../datasources/remote/assessment_api.dart';
import '../datasources/local/local_storage.dart';
import '../../core/constants/storage_keys.dart';
import '../../core/utils/logger.dart';

class AssessmentRepository {
  final AssessmentApi _assessmentApi;
  final LocalStorage _localStorage;

  AssessmentRepository(this._assessmentApi, this._localStorage);

  Future<ApiResponseModel<List<AssessmentModel>>> getAssessments({
    int page = 1,
    int limit = 20,
    String? status,
    String? type,
  }) async {
    try {
      return await _assessmentApi.getAssessments(page: page, limit: limit, status: status, type: type);
    } catch (e) {
      Logger.error('Get assessments error', e);
      return ApiResponseModel(success: false, message: 'Failed to load assessments');
    }
  }

  Future<ApiResponseModel<AssessmentModel>> getAssessmentDetail(String id) async {
    try {
      final response = await _assessmentApi.getAssessmentDetail(id);
      if (response.isSuccess && response.data != null) {
        await _cacheAssessment(response.data!);
      }
      return response;
    } catch (e) {
      Logger.error('Get assessment detail error', e);
      return ApiResponseModel(success: false, message: 'Failed to load assessment details');
    }
  }

  Future<ApiResponseModel<AssessmentModel>> createAssessment(Map<String, dynamic> data) async {
    try {
      return await _assessmentApi.createAssessment(data);
    } catch (e) {
      Logger.error('Create assessment error', e);
      return ApiResponseModel(success: false, message: 'Failed to create assessment');
    }
  }

  Future<ApiResponseModel<AssessmentModel>> updateAssessment(String id, Map<String, dynamic> data) async {
    try {
      return await _assessmentApi.updateAssessment(id, data);
    } catch (e) {
      Logger.error('Update assessment error', e);
      return ApiResponseModel(success: false, message: 'Failed to update assessment');
    }
  }

  Future<ApiResponseModel<void>> submitAssessment(String id, Map<String, dynamic> answers) async {
    try {
      return await _assessmentApi.submitAssessment(id, answers);
    } catch (e) {
      Logger.error('Submit assessment error', e);
      return ApiResponseModel(success: false, message: 'Failed to submit assessment');
    }
  }

  Future<ApiResponseModel<AssessmentModel>> reviewAssessment(String id, Map<String, dynamic> review) async {
    try {
      return await _assessmentApi.reviewAssessment(id, review);
    } catch (e) {
      Logger.error('Review assessment error', e);
      return ApiResponseModel(success: false, message: 'Failed to review assessment');
    }
  }

  Future<ApiResponseModel<dynamic>> getAssessmentProgress(String id) async {
    try {
      return await _assessmentApi.getAssessmentProgress(id);
    } catch (e) {
      Logger.error('Get assessment progress error', e);
      return ApiResponseModel(success: false, message: 'Failed to load progress');
    }
  }

  Future<ApiResponseModel<String>> exportAssessment(String id, String format) async {
    try {
      return await _assessmentApi.exportAssessment(id, format);
    } catch (e) {
      Logger.error('Export assessment error', e);
      return ApiResponseModel(success: false, message: 'Failed to export assessment');
    }
  }

  Future<void> _cacheAssessment(AssessmentModel assessment) async {
    try {
      final key = '${StorageKeys.cacheAssessments}_${assessment.id}';
      await _localStorage.write(key, jsonEncode(assessment.toJson()));
    } catch (e) {
      Logger.error('Cache assessment error', e);
    }
  }

  Future<AssessmentModel?> getCachedAssessment(String id) async {
    try {
      final key = '${StorageKeys.cacheAssessments}_$id';
      final data = await _localStorage.read(key);
      if (data != null) {
        return AssessmentModel.fromJson(jsonDecode(data));
      }
    } catch (e) {
      Logger.error('Get cached assessment error', e);
    }
    return null;
  }
}