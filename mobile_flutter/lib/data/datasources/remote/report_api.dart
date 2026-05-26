import '../api_client.dart';
import '../../models/report_model.dart';
import '../../models/api_response_model.dart';
import '../../../core/constants/api_constants.dart';

class ReportApi {
  final ApiClient _apiClient;

  ReportApi(this._apiClient);

  Future<ApiResponseModel<List<ReportModel>>> getReports({
    int page = 1,
    int limit = 20,
    String? type,
  }) async {
    final response = await _apiClient.get(
      ApiConstants.reports,
      queryParams: {
        'page': page,
        'limit': limit,
        if (type != null) 'type': type,
      },
    );
    
    if (response['success'] == true) {
      final List<dynamic> data = response['data']['items'] ?? [];
      final reports = data.map((item) => ReportModel.fromJson(item)).toList();
      return ApiResponseModel(
        success: true,
        data: reports,
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to load reports',
    );
  }

  Future<ApiResponseModel<ReportModel>> getReportDetail(String id) async {
    final path = ApiConstants.reportDetail.replaceAll('{id}', id);
    final response = await _apiClient.get(path);
    
    if (response['success'] == true) {
      final report = ReportModel.fromJson(response['data']);
      return ApiResponseModel(
        success: true,
        data: report,
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to load report details',
    );
  }

  Future<ApiResponseModel<ReportModel>> generateReport(Map<String, dynamic> data) async {
    final response = await _apiClient.post(ApiConstants.generateReport, data: data);
    
    if (response['success'] == true) {
      final report = ReportModel.fromJson(response['data']);
      return ApiResponseModel(
        success: true,
        data: report,
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to generate report',
    );
  }

  Future<ApiResponseModel<String>> exportReport(String id, String format) async {
    final path = ApiConstants.exportReport.replaceAll('{id}', id);
    final response = await _apiClient.get(path, queryParams: {'format': format});
    
    if (response['success'] == true) {
      return ApiResponseModel(
        success: true,
        data: response['data']['url'],
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to export report',
    );
  }

  Future<ApiResponseModel<void>> shareReport(String id, List<String> recipients, String? message) async {
    final path = ApiConstants.shareReport.replaceAll('{id}', id);
    final response = await _apiClient.post(path, data: {
      'recipients': recipients,
      'message': message,
    });
    
    return ApiResponseModel(
      success: response['success'] == true,
      message: response['message'],
    );
  }

  Future<ApiResponseModel<void>> scheduleReport(Map<String, dynamic> schedule) async {
    final response = await _apiClient.post(ApiConstants.scheduleReport, data: schedule);
    
    return ApiResponseModel(
      success: response['success'] == true,
      message: response['message'],
    );
  }

  Future<ApiResponseModel<void>> deleteReport(String id) async {
    final path = ApiConstants.reportDetail.replaceAll('{id}', id);
    final response = await _apiClient.delete(path);
    
    return ApiResponseModel(
      success: response['success'] == true,
      message: response['message'],
    );
  }
}