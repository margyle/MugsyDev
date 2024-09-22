from fastapi import APIRouter
from operator_app.api.v1.relay.relay_routes import router as relay_router
from operator_app.api.v1.status.status_routes import router as status_router
from operator_app.api.v1.pump.pump_routes import router as pump_router
from operator_app.api.v1.pins.pin_routes import router as pin_router
from operator_app.api.v1.calibrate_scales.calibrate_scales_routes import router as calibrate_scales_router
from operator_app.api.v1.mug_weight.mug_weight_routes import router as mug_weight_router
from operator_app.api.v1.cone_weight.cone_weight_routes import router as cone_weight_router

v1_router = APIRouter()
v1_router.include_router(relay_router, prefix="/relay")
v1_router.include_router(status_router, prefix="/status")
v1_router.include_router(pump_router, prefix="/pump")
v1_router.include_router(pin_router, prefix="/pin")
v1_router.include_router(calibrate_scales_router, prefix="/calibrate-scales")
v1_router.include_router(mug_weight_router, prefix="/mug-weight")
v1_router.include_router(cone_weight_router, prefix="/cone-weight")